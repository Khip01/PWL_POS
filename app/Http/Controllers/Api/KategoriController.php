<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        return KategoriModel::all();
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required|string|size:4|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = KategoriModel::create($request->all());
        return response()->json($kategori, 201);
    }

    public function show(KategoriModel $kategori)
    {
        // return KategoriModel::find($kategori);
        return $kategori;
    }

    public function update(Request $request, KategoriModel $kategori)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'nullable|string|size:4|unique:m_kategori,kategori_kode,' . $kategori->kategori_id . ',kategori_id',
            'kategori_nama' => 'nullable|string|max:100'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori->update([
            'kategori_kode' => $request->kategori_kode ? $request->kategori_kode : $kategori->kategori_kode,
            'kategori_nama' => $request->kategori_nama ? $request->kategori_nama : $kategori->kategori_nama,
        ]);
        // return KategoriModel::find($kategori);
        return $kategori;
        // $kategori->update($request->all());
        // return $kategori;
    }

    public function destroy(KategoriModel $kategori)
    {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
