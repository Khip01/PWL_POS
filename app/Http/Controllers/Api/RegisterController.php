<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        try{

            //set validation
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'nama' => 'required',
                'password' => 'required|min:5|confirmed',
                'level_id' => 'required',
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // profile picture, harus berupa gambar dengan ukuran maksimal 2MB
            ]);
    
            //if validations fails
            if($validator->fails()){
                return response()->json($validator->errors(), 422);
            }
    
            // Menangani upload file gambar
            // Membuat format datetime now dd_mm_yyyy_hh_mm_ss
            $now = now()->format('d_m_Y_H_i_s');
            
            // Simpan file ke storage
            $file = $request->profile_picture; // ambil file dari request 
            // $filename = 'user_' . "added_via_register_" . $now . '.' . $file->getClientOriginalExtension(); // nama file yang disimpan
            $path = $file->storeAs('profile_pictures', $file->hashName(), 'public'); // simpan
        
            //create user
            $user = UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id,
                // 'profile_picture' => $filename // menyimpan path gambar
                'profile_picture' => $file->hashName() // menyimpan path gambar
            ]);
    
            //return response JSON user is created
            if($user){
                return response()->json([
                    'success' => true,
                    'user' => $user,
                ], 201);
            }
    
            //return JSON process insert failed
            return response()->json([
                'success' => false,
            ], 409);

        } catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}