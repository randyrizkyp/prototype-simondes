<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Akunasetkib;
use App\Models\Akunasetbia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkunasetkibController extends Controller
{

    public function viewkib($id)
    {
        $data = Akunasetkib::where([
            'nama_data' => $id,
        ])->get();
        foreach ($data as $dt) {
            $data = $dt->file_data;
        }
        return redirect('/' . 'storage/' . $data);
    }

    public function store(Request $request)
    {
        $cek = Akunasetkib::where([
            'nama_data' => $request->nama_data,
            'asal_id' => $request->asal_id,
            'tahun'    => $request->tahun,
        ])->get();

        if ($cek->isEmpty()) {
            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $request->nama_data,
                'file_data' => $request->file,
            ];
            Akunasetkib::create($data);
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, $request->nama_data . "_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetkib::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $request->nama_data,

            ])->update([
                'file_data' => $file,
            ]);
        } else {
            $hapus = Akunasetkib::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $request->nama_data,
            ])->get('file_data');
            $hps = '';
            foreach ($hapus as $hp) {
                $hps = $hp->file_data;
            }
            Storage::delete($hps);
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, $request->nama_data . "_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetkib::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $request->nama_data,

            ])->update([
                'file_data' => $file,
            ]);
        }
        return back()->with('tambah', 'data berhasil ditambah');
    }
}
