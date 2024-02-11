<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use DataTables;
use App\Models\User; 
use DB;
use Hash;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService; 

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');

        $this->userService = $userService;
    }
 
    public function index()
    {        
        return view("dashboard.users.index");
    }
    public function createUserView()
    {
   
        return view("dashboard.users.create_user");
    }
    public function trashedView()
    {
        return view("dashboard.users.trashed_users");
    }
    public function updateUserView($id)
    {
        $user = User::where("id",$id)->first();
        if($user){
            return view("dashboard.users.update_user")->with(compact("user"));
        }else{
            abort(404);
        }
    }

    public function getUsers()
    {
        $data = User::orderBy("id", "desc")->get();

        return DataTables::of($data)->make(true);
    }
    public function trashed()
    {
        $data = User::withTrashed()
        ->whereNotNull("deleted_at")
        ->orderBy("id", "desc")
        ->get();

        return DataTables::of($data)->make(true);
    }
    public function trashUser(Request $request)
    {
        try {
            // Check if the user is trying to delete themselves
            if (intval($request->id) === intval(Auth::user()->id)) {
                return response()->json(["success" => false, "message" => "You can't delete yourself!"]);
            }

            DB::beginTransaction();

            // Find the user by ID
            $user = User::find($request->id);

            if($user){
                $this->userService->destroy($user->id);
            }else{
                return response()->json(["success" => false, "message" =>"Can't found user with this id"]);
            }
            DB::commit();

            return response()->json(["success" => true, "message" => "User trashed successfully"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }

    public function createUser(Request $request)
    {
        try {

            $rules = [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => ['required', 'max:255', 'unique:users'],
                'gender' => 'required|string|in:mr,mrs,ms',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ];
          
        
            // Validate the request
            $validator = Validator::make($request->all(), $rules);
        
            // If validation fails, retrieve the validation errors
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
        
                // Format errors as HTML list
                $errorList = '<ul>';
                foreach ($errors as $error) {
                    $errorList .= "<li>$error</li>";
                }
                $errorList .= '</ul>';
        
                // Return the HTML list of errors
                return response()->json(['success' => false, 'message' => $errorList]);
            }

            if($request->hasFile("profile")){
                $user_image = $this->userService->upload($request->file('profile'));
            }else{  
                // create defualt profile to user
                $user_image =url('/').'/images/user-defualt.png';
            }
            $user = $this->userService->store([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'middlename' =>$this->userService-> $request->middlename,
                'username' => $request->username,
                'suffixname' => $request->suffixname,
                'type' => $request->type,
                'prefixname' => $request->gender,
                'email' => $request->email,
                'password' =>$this->userService->Hash($request->password),
                'photo' => $user_image,
            ]);
            if($user){
                return response()->json(["success" => true, "message" => "Created successfully"]);
            }else{
                return response()->json(["success" => false, "message" => "Some thing went wrong when try process this request!"]);
            }
        } catch (\Throwable $th) {
            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }


    public function updateUser(UserRequest $request,$id)
    {
        try {
            if(empty($request->user_id)){
                return response()->json(["success" => false, "message" => "Some thing went wrong when try do this request!"]);
            }
   
            $dataUpdated = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'middlename' => $request->middlename,
                'username' => $request->username,
                'suffixname' => $request->suffixname,
                'type' => $request->type,
                'prefixname' => $request->gender,
                'email' => $request->email,
            ];
            if($request->hasFile("profile")){
                $file = $this->userService->upload($request->file('profile'));
                
                // $user->photo = $request->photo;
                $dataUpdated['photo'] = $file;
            }
            if(strtolower($request->update_password) =="on"){
                $dataUpdated['password'] = $this->userService->Hash($request->password);
                
            }

            $user = $this->userService->update($request->user_id,$dataUpdated );

            if($user){
                return response()->json(["success" => true, "message" => "Updated successfully"]);
            }else{
                return response()->json(["success" => false, "message" => "Some thing went wrong when try process this request!"]);
            }
        } catch (\Throwable $th) {
            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }


    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $user = User::withTrashed()->find($id);
            if($user){
                $this->userService->delete($user->id);
                $user->forceDelete(); // Permanently delete the user from the database
            }else{
                return response()->json(["success" => false, "message" =>"Can't found user with this id!"]);
            }
            DB::commit();

            return response()->json(["success" => true, "message" => "User deleted permanently"]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }
    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $user = User::withTrashed()->find($id);
            if($user){
                // Restore the user from the trashed records
                $this->userService->restore($user->id);
            }else{
                return response()->json(["success" => false, "message" =>"Can't found user with this id!"]);
            }
            DB::commit();

            return response()->json(["success" => true, "message" => "User restored Successfully"]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }
}
