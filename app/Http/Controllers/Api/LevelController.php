<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        return LevelModel::all();
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'level_kode' => 'required|string|min:3|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $level = LevelModel::create($request->all());
        return response()->json($level, 201);
    }

    public function show(LevelModel $level)
    {
        return LevelModel::find($level);
    }

    public function update(Request $request, LevelModel $level)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'level_kode' => 'nullable|string|min:3|max:10|unique:m_level,level_kode',
            'level_nama' => 'nullable|string|max:100'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $level->update($request->all());
        return LevelModel::find($level);
    }

    public function destroy(LevelModel $level)
    {
        $level->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}