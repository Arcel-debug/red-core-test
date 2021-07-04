<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Hash;
use Auth;

class UserController extends Controller
{
    public function auth_check(Request $request)
    {
        try {
            return session('auth');
            if ($request->session()->has('auth')) {
                $data = User::find(Crypt::decrypt($request->session()->pull('keyid')));
                return response()->json(['data' => $data, 'result_code' => '1']);
            }
            else
            {
                return response()->json(['result_code' => '0']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function login(Request $request)
    {
        try {
            $data   =   User::where('email', $request['email'])->first();;
            if ($data) {
                if (!$data || !Hash::check($request['password'], $data->password)) {
                    return response()->json(['message' => "Password do not match. Please try again.", 'result_code' => '0']);
                } else {
                    return response()->json(['data' => Crypt::encrypt($data->id), 'result_code' => '1']);
                }
            }
            else {
                return response()->json(['data' => $data, 'message' => "No record found", 'result_code' => '0']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function logout(Request $request)
    {
        try {
            $result = Auth::guard('web')->logout();
            return response()->json(['data' => $result, 'result_code' => '1']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function get_roles(Request $request)
    {
        try {
            $data = Role::all();
            return response()->json(['data' => $data, 'result_code' => '1']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function register(Request $request)
    {
        try {
            $email   =   User::where('email', $request->email)->first();
            $name   =   User::where('name', $request->name)->first();
            if ($name) {
                return response()->json(['message' => 'Name already exist.', 'result_code' => '0']);
            }
            if ($email) {
                return response()->json(['message' => 'Email already exist.', 'result_code' => '0']);
            }

            $result = User::create(['name' => $request->name, 'email' => $request->email, 'role_id' => $request->role_id, 'password' => Hash::make($request->password)]);
            Auth::login($result);
            return response()->json(['data' => Crypt::encrypt($result->id), 'result_code' => '1']);
            
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function get_user_details(Request $request)
    {
        try {
            $data = User::find(Crypt::decrypt($request->keyid));
            return response()->json(['data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function records(Request $request)
    {
        try {
            $data = User::with('role')->get();
            return response()->json(['data' => $data, 'result_code' => '1']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function edit(Request $request)
    {
        try {
            if ($request->keyid == '0') {
                $data = ['id' => 0];
                return response()->json(['data' => $data, 'result_code' => '1']);
            }
            else
            {
                
                $data = User::find($request->keyid);
                if ($data) {
                    return response()->json(['data' => $data, 'result_code' => '1']);
                }
                else
                {
                    return response()->json(['message' => 'Product cannot be found.', 'result_code' => '0']);
                }
                
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function update(Request $request)
    {
        try {
            $requests = $request->all();
            if ($requests['keyid'] == '0') {
                $email   =   User::where('email', $request->email)->first();
                $name   =   User::where('name', $request->name)->first();
                if ($name) {
                    return response()->json(['message' => 'Name already exist.', 'result_code' => '0']);
                }
                if ($email) {
                    return response()->json(['message' => 'Email already exist.', 'result_code' => '0']);
                }
                $data = User::create($requests);
                return response()->json(['data' => $data, 'message' => 'User added successfully', 'result_code' => '1']);
            }
            else
            {
                $data = User::find($requests['keyid']);
                if ($data) {
                    $data->fill($requests);
                    $data->save();
                    return response()->json(['data' => $data, 'message' => 'User updated successfully', 'result_code' => '1']);
                }
                else
                {
                    return response()->json(['message' => 'User not found', 'result_code' => '0']);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = User::find($request->params['keyid']);
            if ($data) {
                $data->forceDelete();
                return response()->json(['message' => 'User deleted successfully', 'result_code' => '1']);
            }
            else
            {
                return response()->json(['message' => 'User not found', 'result_code' => '0']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }
}
