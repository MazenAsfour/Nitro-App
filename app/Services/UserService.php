<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\UserServiceInterface;
use DB;
use Hash;
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
     * @param  int $id
     * @return array
     */
    public function rules($id = null)
    {
        return [
            /**
             * Rule syntax:
             *  'column' => 'validation1|validation2'
             *
             *  or
             *
             *  'column' => ['validation1', function1()]
             */
            'firstname' => 'required',
        ];
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        // Code goes brrrr.
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
     * @return \Illuminate\Database\Eloquent\Model
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
        // Code goes brrrr.
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
        $file= str_replace("public","/storage",$path);
        return $file;
    }
}


?>