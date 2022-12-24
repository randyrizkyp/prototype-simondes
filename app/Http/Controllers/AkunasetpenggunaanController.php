<?php

namespace App\Http\Controllers;

use App\Models\Akunasetbia;
use App\Models\Akunasetkiba;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class AkunasetpenggunaanController extends Controller
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
        $Akunasetpenggunaan = Akunasetbia::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'daftar_status_penggunaan_aset_desa'
        ])->get();

        $Akunasetkiba = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
        ])->orderByRaw("FIELD(lhi , 'tanah', 'kendaraan', 'mesin', 'bangunan', 'jalan', 'lainnya')")->get();

        $Akunasetpenghapusan = Akunasetbia::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'penghapusan_aset_desa'
        ])->get();

        return view('adminDesa.aset.bia.index',  [
            'infos' => $infos,
            'Akunasetpenggunaans' => $Akunasetpenggunaan,
            'Akunasetpenghapusans' => $Akunasetpenghapusan,
            'Akunasetkibas' => $Akunasetkiba,
            'tahun' => $tahun
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        Akunasetbia::create([
            'asal_id'     => $request->asal_id,
            'tahun'       => $request->tahun,
            'nama_data'   => 'daftar_status_penggunaan_aset_desa',
            'no_peraturan' => $request->no_peraturan,
            'file'        => $request->file,
        ]);

        if ($request->file('file')) {
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "daftar_status_penggunaan_aset_desa_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetbia::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'no_peraturan'    => $request->no_peraturan,
                'nama_data' => 'daftar_status_penggunaan_aset_desa'
            ])->update([
                'file' => $file
            ]);
        }

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function updatepenggunaan(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        if ($request->file('file')) {
            if ($request->file_lama && strpos($request->file_lama, $request->tahun)) {
                Storage::delete($request->file_lama);
            }
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "daftar_status_penggunaan_aset_desa_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $Akunasetpenggunaan = Akunasetbia::findOrFail($request->id);
            $Akunasetpenggunaan->update([
                'file' => $file,
                'no_peraturan' => $request->no_peraturan
            ]);
        } else {
            $Akunasetpenggunaan = Akunasetbia::findOrFail($request->id);
            $Akunasetpenggunaan->update([
                'no_peraturan' => $request->no_peraturan
            ]);
        }

        return back()->with('tambah', 'data berhasil diupdate');
    }

    public function hapus(Request $request)
    {
        $data = Akunasetbia::findOrFail($request->id);
        if (strpos($data->file, $request->tahun)) {
            Storage::delete($data->file);
        }
        $data->delete();
        return back();
    }

    public function copyAkunaset(Request $request)
    {

        $datatuju = Akunasetbia::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunasetbia::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunasetbia::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunasetbia::create($data->toArray());
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

        $datas = Akunasetbia::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunasetbia::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    public function addpenghapusan(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        Akunasetbia::create([
            'asal_id'     => $request->asal_id,
            'tahun'       => $request->tahun,
            'nama_data'   => 'penghapusan_aset_desa',
            'no_peraturan'       => $request->no_peraturan,
            'file'        => $request->file,
        ]);

        if ($request->file('file')) {
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "penghapusan_aset_desa_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetbia::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'penghapusan_aset_desa',
                'no_peraturan'     => $request->no_peraturan,
            ])->update([
                'file' => $file
            ]);
        }

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function updatepenghapusan(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        if ($request->file('file')) {
            if ($request->file_lama && strpos($request->file_lama, $request->tahun)) {
                Storage::delete($request->file_lama);
            }
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "penghapusan_aset_desa_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $Akunasetpenghapusan = Akunasetbia::findOrFail($request->id);
            $Akunasetpenghapusan->update([
                'file' => $file,
                'no_peraturan' => $request->no_peraturan
            ]);
        } else {
            $Akunasetpenghapusan = Akunasetbia::findOrFail($request->id);
            $Akunasetpenghapusan->update([
                'no_peraturan' => $request->no_peraturan
            ]);
        }

        return back()->with('tambah', 'data berhasil diupdate');
    }

    public function addbia(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        Akunasetbia::create([
            'asal_id'     => $request->asal_id,
            'tahun'       => $request->tahun,
            'nama_data'   => 'buku_inventarisasi_aset',
            'file'        => $request->file,
        ]);

        if ($request->file('file')) {
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "buku_inventarisasi_aset_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetbia::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'buku_inventarisasi_aset',
            ])->update([
                'file' => $file
            ]);
        }

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function biaupdate(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        if ($request->file('file')) {
            if ($request->old_bia && strpos($request->old_bia, $request->tahun)) {
                Storage::delete($request->old_bia);
            }
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "buku_inventarisasi_aset_" . $request->tahun . mt_rand(1, 10) . "." . $ext);
            $Akunasetpenghapusan = Akunasetbia::findOrFail($request->id);
            $Akunasetpenghapusan->update([
                'file' => $file,
            ]);
        }

        return back()->with('tambah', 'data berhasil diupdate');
    }
}
