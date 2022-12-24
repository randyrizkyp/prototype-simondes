<?php

namespace App\Http\Controllers;

use App\Models\Akunasetholder;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class AkunasetholderController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $Akunasetholder = Akunasetholder::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        return view('adminDesa.aset.holder.index',  [
            'infos' => $infos,
            'Akunasetholders' => $Akunasetholder,
            'tahun' => $tahun
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
            'nama_barang'        => 'required',
            'nama'        => 'required',
        ]);
        Akunasetholder::create([
            'asal_id'     => $request->asal_id,
            'tahun'       => $request->tahun,
            'nama_barang' => $request->nama_barang,
            'nama' => $request->nama,
            'file'        => $request->file,
        ]);
        $nama_barang = str_replace(' ', '_', $request->nama_barang);
        $nama_barangs = strtolower($nama_barang);

        if ($request->file('file')) {
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "holder_" . $nama_barangs . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetholder::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_barang'    => $request->nama_barang,
                'nama' => $request->nama
            ])->update([
                'file' => $file
            ]);
        }

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function update(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
            'nama_barang'        => 'required',
            'nama'        => 'required',
        ]);
        $nama_barang = str_replace(' ', '_', $request->nama_barang);
        $nama_barangs = strtolower($nama_barang);
        if ($request->file('file')) {
            if ($request->file_lama && strpos($request->file_lama, $request->tahun)) {
                Storage::delete($request->file_lama);
            }
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "holder_" . $nama_barangs . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $Akunasetholder = Akunasetholder::findOrFail($request->id);
            $Akunasetholder->update([
                'file' => $file,
                'nama_barang' => $request->nama_barang
            ]);
        }

        $Akunasetholder = Akunasetholder::findOrFail($request->id);
        if ($request->nama_barang) {
            $Akunasetholder->update([
                'nama_barang' => $request->nama_barang
            ]);
        }
        if ($request->nama) {
            $Akunasetholder->update([
                'nama' => $request->nama
            ]);
        }


        return back()->with('tambah', 'data berhasil diupdate');
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy(Akunasetholder $Akunasetholder)
    {
        $data = Akunasetholder::findOrFail($Akunasetholder->id);
        $data->delete();
        if (strpos($Akunasetholder->file, $Akunasetholder->tahun)) {
            Storage::delete($data->file);
        }
        return back()->with('hapus', 'data berhasil dihapus');
    }

    public function copyAkunaset(Request $request)
    {

        $datatuju = Akunasetholder::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunasetholder::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunasetholder::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunasetholder::create($data->toArray());
            }

            if ($copydata) {
                return redirect()->back()->with('success', 'Data berhasil di copy');
            }

            exit();
            die();
        }

        if ($datatuju > 0) {
            return back()->with('timpaAll', $request->tahuncopy);
            exit();
            die();
        }

        $datas = Akunasetholder::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunasetholder::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
