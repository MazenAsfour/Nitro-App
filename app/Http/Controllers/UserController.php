<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\User; 
use DB;
use Hash;
use Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view("dashboard.index");
    }
    public function createUserView()
    {
        return view("dashboard.create_user");
    }
    public function trashedView()
    {
        return view("dashboard.trashed_users");
    }
    public function updateUserView($id)
    {
        $user = User::where("id",$id)->first();
        if($user){
            return view("dashboard.update_user")->with(compact("user"));
        }else{
            abort(404);
        }
    }

    public function getUsers()
    {
        $data = User::orderBy("id", "desc")->get();

        return DataTables::of($data)->make(true);
    }
    public function getTrashedUsers()
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
                // Soft delete the user
                $user->delete();
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
            DB::beginTransaction();
            $request->merge(['username' => $request->firstname . ' ' . $request->lastname]);

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
                $path = $request->file('profile')->store('public/images');
                $user_image= str_replace("public","/storage",$path);
                $updatingTheirImage = true;
            }else{  
                // create defualt profile to user
                $user_image ='/images/user-defualt.png';
            }

            User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'middlename' => $request->middlename,
                'username' => $request->username,
                'suffixname' => $request->suffixname,
                'type' => $request->type,
                'prefixname' => $request->gender,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'photo' => $user_image,
            ]);
      
            DB::commit();

            return response()->json(["success" => true, "message" => "Created successfully"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }


    public function updateUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->merge(['username' => $request->firstname . ' ' . $request->lastname]);

            $rules = [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => ['required', 'max:255', 'unique:users,username,' . $request->user_id],
                'gender' => 'required|string|in:mr,mrs,ms',
                'email' => 'required|email|unique:users,email,' . $request->user_id,
                'password' => 'required|string|min:8|confirmed',
            ];

            if(strtolower($request->update_password) !=="on"){
                //here on update page when user need update data without passoword
                unset($rules['password']);
            }
            
        
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
            if(empty($request->user_id)){
                return response()->json(["success" => false, "message" => "Some thing went wrong when try do this request!"]);
            }

        
            $user = User::find($request->user_id);

            if($request->hasFile("profile")){
                $path = $request->file('profile')->store('public/images');
                $user_image= str_replace("public","/storage",$path);
                $user->photo = $request->photo;
            }
            if(strtolower($request->update_password) =="on"){
                $user->password = Hash::make($request->password);
            }

            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->middlename = $request->middlename;
            $user->username = $request->username;
            $user->suffixname = $request->suffixname;
            $user->type = $request->type;
            $user->prefixname = $request->gender;
            $user->email = $request->email;
            $user->save();

            DB::commit();

            return response()->json(["success" => true, "message" => "Updated successfully"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $th->getMessage()]);
        }
    }

    public function deleteUserPermently(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::withTrashed()->find($request->id);
            if($user){
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
    public function restoreUser(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::withTrashed()->find($request->id);
            if($user){
                $user->restore(); // Restore the user from the trashed records
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
