<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Akunadum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkunadumController extends Controller
{
    public function formAkunadum(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();

        $dataAkunadum = Akunadum::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get()->count();

        if ($dataAkunadum == 0) {
            return view('adminDesa.akunadum.form_akunadum', [
                'tahun' => $tahun,
                'infos' => $infos,
            ]);
        } else {
            return view('adminDesa.akunadum.form_akunadum_e', [
                'tahun' => $tahun,
                'infos' => $infos,
                'dataAkun' => Akunadum::where([
                    'asal_id' => $infos->asal_id,
                    'tahun' => $tahun
                ])->get()

            ]);
        }
    }

    public function tambahAkunadum(Request $request)
    {
        $request->validate([
            'upload_surat' => 'mimes:pdf|max:10240',
            'upload_daftar_hadir' => 'mimes:pdf|max:10240',
            'upload_daftar_hadir2' => 'mimes:pdf|max:10240',
            'upload_buku_register' => 'mimes:pdf|max:10240',
            'upload_rekap_penduduk' => 'mimes:pdf|max:10240',
        ]);
        foreach ($request->nama_data as $nm) {
            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $nm,
            ];
            Akunadum::create($data);
        }
        if ($request->file('upload_surat')) {
            $ext = $request->upload_surat->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_surat')->storeAs($folder, "upload_surat_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'surat',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_daftar_hadir')) {
            $ext = $request->upload_daftar_hadir->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_daftar_hadir')->storeAs($folder, "upload_daftar_hadir_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'daftar_hadir',

            ])->update([
                'file_data' => $file,

            ]);
        }
        if ($request->file('upload_daftar_hadir2')) {
            $ext = $request->upload_daftar_hadir2->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_daftar_hadir2')->storeAs($folder, "upload_daftar_hadir2_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'daftar_hadir2',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_buku_register')) {
            $ext = $request->upload_buku_register->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_buku_register')->storeAs($folder, "upload_buku_register_" . $request->tahun . mt_rand(1, 20) . "." . $ext);
            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'buku_register',
            ])->update([
                'file_data' => $file,
            ]);
        }

        if ($request->file('upload_rekap_penduduk')) {
            $ext = $request->upload_rekap_penduduk->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_rekap_penduduk')->storeAs($folder, "upload_rekap_penduduk_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'rekap_penduduk',
            ])->update([
                'file_data' => $file,
            ]);
        }
        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function updateAkunadum(Request $request)
    {
        $request->validate([
            'upload_surat' => 'mimes:pdf|max:10240',
            'upload_daftar_hadir' => 'mimes:pdf|max:10240',
            'upload_daftar_hadir2' => 'mimes:pdf|max:10240',
            'upload_buku_register' => 'mimes:pdf|max:10240',
            'upload_rekap_penduduk' => 'mimes:pdf|max:10240',
        ]);
        if ($request->file('upload_surat')) {
            if ($request->old_0 && strpos($request->old_0, $request->tahun)) {
                Storage::delete($request->old_0);
            }
            $ext = $request->upload_surat->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_surat')->storeAs($folder, "upload_surat_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'surat'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_daftar_hadir')) {
            if ($request->old_1 && strpos($request->old_1, $request->tahun)) {
                Storage::delete($request->old_1);
            }
            $ext = $request->upload_daftar_hadir->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_daftar_hadir')->storeAs($folder, "upload_daftar_hadir_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'daftar_hadir'
            ])->update([
                'file_data' => $file
            ]);
        }
        if ($request->file('upload_daftar_hadir2')) {
            if ($request->old_2 && strpos($request->old_2, $request->tahun)) {
                Storage::delete($request->old_2);
            }
            $ext = $request->upload_daftar_hadir->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_daftar_hadir2')->storeAs($folder, "upload_daftar_hadir2_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'daftar_hadir2'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_buku_register')) {
            if ($request->old_3 && strpos($request->old_3, $request->tahun)) {
                Storage::delete($request->old_3);
            }
            $ext = $request->upload_buku_register->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_buku_register')->storeAs($folder, "upload_buku_register_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'buku_register'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_rekap_penduduk')) {
            if ($request->old_4 && strpos($request->old_4, $request->tahun)) {
                Storage::delete($request->old_4);
            }
            $ext = $request->upload_rekap_penduduk->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_administrasi_umum";
            $file = $request->file('upload_rekap_penduduk')->storeAs($folder, "upload_rekap_penduduk_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunadum::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'rekap_penduduk'
            ])->update([
                'file_data' => $file
            ]);
        }

        return back()->with('update', 'data berhasil update');
    }

    public function copyAkunadum(Request $request)
    {

        $datatuju = Akunadum::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunadum::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunadum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunadum::create($data->toArray());
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

        $datas = Akunadum::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunadum::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
