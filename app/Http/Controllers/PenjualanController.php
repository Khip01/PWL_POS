<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\DataTables;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object)[
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        $user = UserModel::all(); // ambil data user untuk filter user

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        // Filter data berdasarkan user
        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_ajax()
    {
        $user = UserModel::select('user_id', 'username')->get();
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_jual')->get();

        return view('penjualan.create_ajax')
            ->with('user', $user)
            ->with('barang', $barang);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_ajax(Request $request)
    {
        // Validate main form
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_user,user_id',
            'pembeli' => 'required|string|max:50',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:m_barang,barang_id',
            'jumlah.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();

            // Generate kode penjualan
            $lastId = PenjualanModel::max('penjualan_id') ?? 0;
            $newId = $lastId + 1;
            $kode = 'PJ-' . str_pad($newId, 4, '0', STR_PAD_LEFT);

            // Create penjualan
            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $kode,
                'penjualan_tanggal' => $request->penjualan_tanggal
            ]);

            // Create penjualan details
            $barang_ids = $request->barang_id;
            $harga = $request->harga;
            $jumlah = $request->jumlah;

            for ($i = 0; $i < count($barang_ids); $i++) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_ids[$i],
                    'harga' => $harga[$i],
                    'jumlah' => $jumlah[$i]
                ]);

                // *optional: Update stock if needed
                // $barang = BarangModel::find($barang_ids[$i]);
                // $barang->stok -= $jumlah[$i];
                // $barang->save();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan',
                'data' => $penjualan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['penjualanDetail.barang', 'user'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ]);
        }

        return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['penjualanDetail.barang', 'user'])->find($id);
        $user = UserModel::all();
        $barang = BarangModel::all();

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ]);
        }

        return view('penjualan.edit_ajax', compact('penjualan', 'user', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_ajax(Request $request, string $id)
    {
        // Validate main form
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:m_user,user_id',
            'pembeli' => 'required|string|max:50',
            'penjualan_tanggal' => 'required|date',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:m_barang,barang_id',
            'jumlah.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();

            // Find penjualan
            $penjualan = PenjualanModel::find($id);

            if (!$penjualan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }

            // Update penjualan
            $penjualan->update([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_tanggal' => $request->penjualan_tanggal
            ]);

            // Delete existing details
            PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->delete();

            // Create new penjualan details
            $barang_ids = $request->barang_id;
            $harga = $request->harga;
            $jumlah = $request->jumlah;

            for ($i = 0; $i < count($barang_ids); $i++) {
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_ids[$i],
                    'harga' => $harga[$i],
                    'jumlah' => $jumlah[$i]
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil diperbarui',
                'data' => $penjualan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete_ajax(string $id)
    {
        try {
            DB::beginTransaction();

            // Find penjualan
            $penjualan = PenjualanModel::find($id);

            if (!$penjualan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }

            // Delete details first (due to foreign key constraints)
            PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->delete();

            // Delete penjualan
            $penjualan->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['penjualanDetail.barang', 'user'])->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ]);
        }

        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_penjualan');  // ambil file dari request

            try {
                $reader = IOFactory::createReader('Xlsx');  // load reader file excel
                $reader->setReadDataOnly(true);             // hanya membaca data
                $spreadsheet = $reader->load($file->getRealPath()); // load file excel
                $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif
                $data = $sheet->toArray(null, false, true, true);   // ambil data excel

                // Mulai transaksi database untuk memastikan integritas data
                DB::beginTransaction();

                try {
                    $insertedCount = 0;
                    $currentPenjualanId = null;

                    // Skip baris 1 karena header Excel
                    if(count($data) > 1){
                        foreach ($data as $baris => $value) {
                            if($baris > 1) { // Skip baris header (baris 1)
                                // Cek apakah ini penjualan baru atau lanjutan
                                // Jika user_id, pembeli, penjualan_kode, dan penjualan_tanggal ada, ini adalah penjualan baru
                                if (!empty($value['A']) && !empty($value['B']) && !empty($value['C']) && !empty($value['D'])) {
                                    // Data penjualan header
                                    $penjualan = PenjualanModel::create([
                                        'user_id' => $value['A'],
                                        'pembeli' => $value['B'],
                                        'penjualan_kode' => $value['C'],
                                        'penjualan_tanggal' => $value['D'],
                                    ]);

                                    $currentPenjualanId = $penjualan->penjualan_id;
                                    $insertedCount++;
                                }

                                // Tambahkan detail penjualan jika barang_id tidak kosong
                                if (!empty($value['E']) && $currentPenjualanId) {
                                    PenjualanDetailModel::create([
                                        'penjualan_id' => $currentPenjualanId,
                                        'barang_id' => $value['E'],
                                        'harga' => $value['F'],
                                        'jumlah' => $value['G'],
                                    ]);
                                }
                            }
                        }

                        // Commit transaksi jika semua berhasil
                        DB::commit();

                        if ($insertedCount > 0) {
                            return response()->json([
                                'status' => true,
                                'message' => 'Data penjualan berhasil diimport'
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'Tidak ada data yang diimport'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'File Excel tidak memiliki data'
                        ]);
                    }
                } catch (\Exception $e) {
                    // Rollback jika terjadi error
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal import data: ' . $e->getMessage()
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal membaca file Excel: ' . $e->getMessage(),
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
    {
        // Ambil data penjualan beserta detailnya
        $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang'])
            ->orderBy('penjualan_tanggal', 'desc')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        // Set header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Pembeli');
        $sheet->setCellValue('E1', 'User');
        $sheet->setCellValue('F1', 'Kode Barang');
        $sheet->setCellValue('G1', 'Nama Barang');
        $sheet->setCellValue('H1', 'Harga');
        $sheet->setCellValue('I1', 'Jumlah');
        $sheet->setCellValue('J1', 'Subtotal');
        $sheet->getStyle('A1:J1')->getFont()->setBold(true); // bold header

        // Loop data penjualan dan masukkan ke dalam sheet
        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2

        foreach ($penjualan as $p) {
            $firstRow = true;

            // Loop detail penjualan
            foreach ($p->penjualanDetail as $detail) {
                $sheet->setCellValue('A' . $baris, $firstRow ? $no : '');
                $sheet->setCellValue('B' . $baris, $firstRow ? $p->penjualan_kode : '');
                $sheet->setCellValue('C' . $baris, $firstRow ? $p->penjualan_tanggal : '');
                $sheet->setCellValue('D' . $baris, $firstRow ? $p->pembeli : '');
                $sheet->setCellValue('E' . $baris, $firstRow ? $p->user->username : '');
                $sheet->setCellValue('F' . $baris, $detail->barang->barang_kode);
                $sheet->setCellValue('G' . $baris, $detail->barang->barang_nama);
                $sheet->setCellValue('H' . $baris, $detail->harga);
                $sheet->setCellValue('I' . $baris, $detail->jumlah);
                $sheet->setCellValue('J' . $baris, $detail->harga * $detail->jumlah);

                $baris++;
                $firstRow = false;
            }

            $no++;
        }

        // Set lebar kolom
        foreach(range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        // Proses export excel
        $sheet->setTitle('Data Penjualan'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        // Ambil data penjualan beserta detailnya
        $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang'])
            ->orderBy('penjualan_tanggal', 'desc')
            ->get();

        // Menghitung total untuk setiap penjualan
        $penjualan->map(function($p) {
            $p->total = $p->penjualanDetail->sum(function($detail) {
                return $detail->harga * $detail->jumlah;
            });
            return $p;
        });

        // Use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data Penjualan '.date('Y-m-d H:i:s').'.pdf');
    }
}
