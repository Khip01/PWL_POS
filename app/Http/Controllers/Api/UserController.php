<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        return UserModel::all();
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:m_user,username',
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5', // password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer' // level id harus diisi dan berupa angka
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserModel::create($request->all());
        return response()->json($user, 201);
    }

    public function show(UserModel $user)
    {
        // return UserModel::find($user);
        return $user;
    }

    public function update(Request $request, UserModel $user)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'username' => 'nullable|string|min:3|max:20|unique:m_user,username,' . $user->user_id . ',user_id',
            'nama' => 'nullable|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'nullable|integer' // level id harus diisi dan berupa angka
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'username' => $request->username ? $request->username : $user->username,
            'nama' => $request->nama ? $request->nama : $user->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id ? $request->level_id : $user->level_id,
        ]);
        // return UserModel::find($user);
        return $user;
        // $user->update($request->all());
        // return $user;
    }

    public function destroy(UserModel $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
