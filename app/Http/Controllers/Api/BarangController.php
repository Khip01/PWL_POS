<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::all();
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'required|string|min:7|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // image, harus berupa gambar dengan ukuran maksimal 2MB
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Menangani upload file gambar
        // store ke storage
        $file = $request->image; // ambil file dari request
        $file->storeAs('barang_images', $file->hashName(), 'public'); // simpan

        $barang = BarangModel::create(array_merge($request->all(), ['image' => $file->hashName()]));
        return response()->json($barang, 201);
    }

    public function show(BarangModel $barang)
    {
        // return BarangModel::find($barang);
        return $barang;
    }

    public function update(Request $request, BarangModel $barang)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'barang_kode' => 'nullable|string|min:7|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'nullable|string|max:100',
            'harga_beli' => 'nullable|integer',
            'harga_jual' => 'nullable|integer',
            'kategori_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // image, harus berupa gambar dengan ukuran maksimal 2MB
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        if ($request->hasFile('image')) {
            // hapus dulu barang yang pernah ada
            if ($barang->image) {
                $path = storage_path('app\\public\\barang_images\\' . $barang->getRawOriginal('image'));
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $file = $request->image; // ambil file dari request
            $file->storeAs('barang_images', $file->hashName(), 'public'); // simpan
            $barang->update(['image' => $file->hashName()]);
        }

        $barang->update([
            'barang_kode' => $request->barang_kode ? $request->barang_kode : $barang->barang_kode,
            'barang_nama' => $request->barang_nama ? $request->barang_nama : $barang->barang_nama,
            'harga_beli' => $request->harga_beli ? $request->harga_beli : $barang->harga_beli,
            'harga_jual' => $request->harga_jual ? $request->harga_jual : $barang->harga_jual,
            'kategori_id' => $request->kategori_id ? $request->kategori_id : $barang->kategori_id,
        ]);
        // return BarangModel::find($barang);
        return response()->json($barang, 200);
        // $barang->update($request->all());
        // return $barang;
    }

    public function destroy(BarangModel $barang)
    {
        $barang->delete();

        // Hapus file gambar dari storage
        if ($barang->image) {
            $path = storage_path('app\\public\\barang_images\\' . $barang->getRawOriginal('image'));
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
