<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function edit(Request $request)
    {
        try {
            if ($request->keyid == '0') {
                $data = ['id' => 0];
                return response()->json(['data' => $data, 'result_code' => '1']);
            }
            else
            {
                
                $data = Role::find($request->keyid);
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
                $data = Role::create($requests);
                return response()->json(['data' => $data, 'message' => 'Role added successfully', 'result_code' => '1']);
            }
            else
            {
                $data = Role::find($requests['keyid']);
                if ($data) {
                    $data->fill($requests);
                    $data->save();
                    return response()->json(['data' => $data, 'message' => 'Role updated successfully', 'result_code' => '1']);
                }
                else
                {
                    return response()->json(['message' => 'Role not found', 'result_code' => '0']);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = Role::find($request->params['keyid']);
            if ($data) {
                $data->forceDelete();
                return response()->json(['message' => 'Role deleted successfully', 'result_code' => '1']);
            }
            else
            {
                return response()->json(['message' => 'Role not found', 'result_code' => '0']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }

    public function records(Request $request)
    {
        try {
            $data = Role::all();
            return response()->json(['data' => $data, 'result_code' => '1']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'result_code' => '0']);
        }
    }
}
