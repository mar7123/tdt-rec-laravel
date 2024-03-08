<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{
    public function createAccount(Request $request): Response
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'type' => 'required|in:User,Admin',
            ]);
            if ($validateUser->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $salt = Str::random(10);
            $type_id = Role::where('name', $request->type)->first();
            $user = User::forceCreate([
                'name' => $request->name,
                'salt' => $salt,
                'password' => Hash::make($request->password . $salt),
                'email' => $request->email,
                'user_role_id' => $type_id->role_id
            ]);
            return Response([
                'status' => true,
                'message' => 'Account created successfully',
            ], 201);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function loginUser(Request $request): Response
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validateUser->fails()) {
                return Response([
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $saltres = User::select('salt')->where('email', $request->email)->first();
            if ($saltres == null) {
                return Response([
                    'status' => false,
                    'message' => 'account not found'
                ], 401);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password . $saltres->salt])) {
                $user = User::where('email', $request->email)->first();
                $login_token = $user->tokens()->where('name', 'Login Token')->get();
                if ($login_token->first() != null) {
                    foreach ($login_token as $lt) {
                        $lt->delete();
                    }
                }
                $request->session()->regenerate();
                $expiry = new DateTime();
                $expiry->modify('+1 hour');
                $success =  $user->createToken('Login Token', ['*'], $expiry)->plainTextToken;
                $tkn = explode("|", $success);
                return Response([
                    'status' => true,
                    'message' => 'Logged in successfully',
                    'token' => $tkn[1]
                ], 200);
            }
            return Response([
                'status' => false,
                'message' => 'email or password wrong'
            ], 401);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request): Response
    {
        try {
            $request->user()->tokens()->delete();
            return Response(['data' => 'Logout successfully.'], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getUsers(Request $request): Response
    {
        try {
            $users = User::all();
            return Response([
                'status' => true,
                'data' => $users,
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createUser(Request $request): Response
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
            if ($validateUser->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $salt = Str::random(10);
            $type_id = Role::where('name', 'User')->first();
            $user = User::forceCreate([
                'name' => $request->name,
                'salt' => $salt,
                'password' => Hash::make($request->password . $salt),
                'email' => $request->email,
                'user_role_id' => $type_id->role_id
            ]);
            return Response([
                'status' => true,
                'message' => 'User created successfully',
            ], 201);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function updateUser(Request $request): Response
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'user_id' => 'required|uuid|exists:users,user_id',
                'name' => 'required',
                'email' => 'required|email',
            ]);
            if ($validateUser->fails()) {
                return Response([
                    'status' => false,
                    'message' => 'validation_error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::where('user_id', $request->user_id)->first();
            $user->update($request->all());
            return Response([
                'status' => true,
                'message' => 'account updated'
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function deleteUser(Request $request): Response
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'user_id' => 'required|uuid|exists:users,user_id',
            ]);
            if ($validateUser->fails()) {
                return Response([
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::where('user_id', $request->user_id)->first();
            $user->delete();
            return Response([
                'status' => true,
                'message' => 'deleted successfully'
            ], 200);
        } catch (Throwable $th) {
            return Response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
