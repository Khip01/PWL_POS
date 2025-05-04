<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data user yang sedang login
        $user = auth()->user();

        $breadcrumb = (object) [
            'title' => 'Profil Pengguna',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $activeMenu = 'profile';

        return view('profile.index')->with(['user' => $user, 'activeMenu' => $activeMenu, 'page' => $page, 'breadcrumb' => $breadcrumb]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function import()
    {
        return view('profile.import');
    }
    
    public function import_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus png atau jpg, max 1MB 
                'file_user' => ['required', 'mimes:png,jpg,jpeg', 'max:102400'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            try{
            
                $user = auth()->user(); // ambil data user yang sedang login
                
                // $file = $request->file('file_user');  // ambil file dari request 
                // $file_path = 'profile_pictures/' . $file->hashName(); // path file yang disimpan di database

                // // Update foto di database
                // $user->profile_picture = $file_path; // set profile_picture ke path file
                // $user->save(); // simpan ke database

                // simpan gambar ke public/profile
                // $destinationPath = public_path('profile_pictures'); // path ke public/profile_pictures
                // $file->move($destinationPath, $file->hashName()); // simpan file di folder public

                // Hapus file lama jika ada
                if ($user->profile_picture) {
                    $oldFilePath = public_path($user->profile_picture); // path file lama
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath); // hapus file lama
                    }
                }

                // Simpan file ke storage
                // $path = $request->file('file_user')->storeAs('profile_pictures', $request->file('file_user')->hashName(), 'public');
                $file = $request->file('file_user'); // ambil file dari request 
                $filename = 'user_' . $user->user_id . '.' . $file->getClientOriginalExtension(); // nama file yang disimpan
                $path = $file->storeAs('profile_pictures', $filename, 'public'); // simpan

                // Menyimpan URL ke database
                $user->profile_picture = Storage::url($path); // set profile_picture ke path file
                // $user->profile_picture = $filename; // set profile_picture ke path file
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan file: ' . $th->getMessage(),
                ]);

            }
        } 
    }
}
