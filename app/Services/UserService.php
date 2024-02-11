<?php

namespace App\Services;

use App\Models\User;
use App\Models\Detail;
use App\Events\UserSaved;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\UserServiceInterface;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserService implements UserServiceInterface
{
    /**
     * The model instance.
     *
     * @var App\User
     */
    protected $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  $request  
     * @return array
     */
    public function rules($request)
{
    // Initialize an empty array for rules
    $rules = [];

    // Define common rules for create and update
    $commonRules = [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'gender' => 'required|string|in:Mr,Mrs,Ms',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',

    ];

    // Add common rules to the main rules array
    $rules = $commonRules;

    // Check if this is an update request (has user_id)
    if (isset($request["user_id"])) {
        // Add specific rules for update request
        $rules['username'] = ['required', 'max:255', 'unique:users,username,' . $request["user_id"]];
        $rules['email'] = 'required|email|unique:users,email,' . $request["user_id"];
    } else {
        // Add specific rules for create request
        $rules['username'] = ['required', 'max:255', 'unique:users,username'];
        $rules['email'] = 'required|email|unique:users,email';
    }
    // Check if the 'update_password' key is set to update the password when the checkbox is off. If the checkbox is off, the 'update_password' index won't be present in the request data.
    if (!isset($request['update_password'])) {
        unset($rules['password']);
    }

    if (isset($request['photo'])) {
        $rules['photo'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
    }

    return $rules;
}

    /**
     * Retrieve all resources and paginate.
     *
     * @return DataTables
     */
    public function getPaginatedUsersDatatable()
    {
        $data = User::orderBy("id","desc")->get();

        return DataTables::of($data)->make(true);
    }
    /**
     * Retrieve all resources and paginate.
     *
     * @return DataTables
     */
    public function getPaginatedTrashedUsers()
    {
        $data = User::withTrashed()
        ->whereNotNull("deleted_at")
        ->orderBy("id", "desc")
        ->get();

        return DataTables::of($data)->make(true);
    }
    /**
     * Get a paginated list of users.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedUsers(): LengthAwarePaginator
    {
        // Retrieve paginated list of users using the User model
        return User::paginate(10); // Adjust the pagination limit as needed
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \App\Models\User
     */
    public function store(array $attributes)
    {
        return User::create($attributes);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        try {
            DB::beginTransaction();

            User::where("id",$id)->update($attributes);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id): bool
    {
        try {
            DB::beginTransaction();
            User::where('id', $id)->delete();
            DB::commit();
            // If the update was successful, return true
            return true;
        } catch (\Throwable $th) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed() : LengthAwarePaginator
    {
        return User::onlyTrashed()->paginate(10);
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();
                User::withTrashed()->where('id', $id)->restore();
            DB::commit();
            // If the update was successful, return true
        } catch (\Throwable $th) {
            DB::rollback();
        }

    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
                $user = User::withTrashed()->find($id);
                $user->forceDelete(); // Permanently delete the user from the database
            DB::commit();
            // If the update was successful, return true
        } catch (\Throwable $th) {
            DB::rollback();
        }
        
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return Hash::make($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        $path = $file->store('public/images');
        $new_path= str_replace("public","/storage",$path);
        return url('/').$new_path;
    }

    /**
    * Save user details.
    *
    * @param  App\Model\User $user
    */
    public function saveUserDetails(User $user)
    {
        $fullName = $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname;
        $middleInitial = substr($user->middlename, 0, 1);
        $avatar = $user->photo; // assuming 'photo' is the attribute for the user's photo
        $gender = $user->prefixname; // assuming 'prefixname' is the attribute for the user's gender

        // Save to details table
        Detail::create([
            'key' => 'full_name',
            'value' => $fullName,
            'user_id' => $user->id
        ]);

        Detail::create([
            'key' => 'middle_initial',
            'value' => $middleInitial,
            'user_id' => $user->id
        ]);

        Detail::create([
            'key' => 'avatar',
            'value' => $avatar,
            'user_id' => $user->id
        ]);

        Detail::create([
            'key' => 'gender',
            'value' => $gender,
            'user_id' => $user->id
        ]);
    }
}


?>