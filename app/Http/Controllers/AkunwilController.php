<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Akunwil;
use App\Models\Datum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkunwilController extends Controller
{
    public function formAkunwil(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $dawil = Datum::where([
            'jenis' => 'kewilayahan',
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();


        if (!count($dawil)) {
            return redirect('/adminDesa/formKewilayahan?jenis=kewilayahan&tahun=' . $tahun)->with('kosong', 'data kosong, data umum kewilayahan tahun ' . $tahun . ' harus diinput terlebih dahulu! ');
        }

        $dataAkunwil = Akunwil::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get()->count();

        if ($dataAkunwil == 0) {
            return view('adminDesa.akunwil.form_akunwil', [
                'tahun' => $tahun,
                'infos' => $infos,
                'dawil' => $dawil

            ]);
        } else {
            return view('adminDesa.akunwil.form_akunwil_e', [
                'tahun' => $tahun,
                'infos' => $infos,
                'dawil' => $dawil,
                'dataAkun' => Akunwil::where([
                    'asal_id' => $infos->asal_id,
                    'tahun' => $tahun
                ])->get()

            ]);
        }
    }

    public function tambahAkunwil(Request $request)
    {
        foreach ($request->nama_data as $nm) {
            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $nm
            ];
            Akunwil::create($data);
        }
        if ($request->file('upload_dasar_hukum')) {
            $ext = $request->upload_dasar_hukum->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_dasar_hukum')->storeAs($folder, "upload_dasar_hukum_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'dasar_hukum'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_utara')) {
            $ext = $request->upload_batas_utara->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_utara')->storeAs($folder, "upload_batas_utara_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_utara'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_selatan')) {
            $ext = $request->upload_batas_selatan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_selatan')->storeAs($folder, "upload_batas_selatan_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_selatan'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_barat')) {
            $ext = $request->upload_batas_barat->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_barat')->storeAs($folder, "upload_batas_barat_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_barat'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_timur')) {
            $ext = $request->upload_batas_timur->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_timur')->storeAs($folder, "upload_batas_timur_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_timur'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_peta_batas')) {
            $ext = $request->upload_peta_batas->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_peta_batas')->storeAs($folder, "upload_peta_batas_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'peta_batas'
            ])->update([
                'file_data' => $file
            ]);
        }
        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function updateAkunwil(Request $request)
    {

        if ($request->file('upload_dasar_hukum')) {
            if ($request->old_0) {
                Storage::delete($request->old_0);
            }
            $ext = $request->upload_dasar_hukum->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_dasar_hukum')->storeAs($folder, "upload_dasar_hukum_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'dasar_hukum'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_utara')) {
            if ($request->old_1) {
                Storage::delete($request->old_1);
            }
            $ext = $request->upload_batas_utara->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_utara')->storeAs($folder, "upload_batas_utara_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_utara'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_selatan')) {
            if ($request->old_2) {
                Storage::delete($request->old_2);
            }
            $ext = $request->upload_batas_selatan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_selatan')->storeAs($folder, "upload_batas_selatan_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_selatan'
            ])->update([
                'file_data' => $file
            ]);
        }



        if ($request->file('upload_batas_barat')) {
            if ($request->old_3) {
                Storage::delete($request->old_3);
            }
            $ext = $request->upload_batas_barat->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_barat')->storeAs($folder, "upload_batas_barat_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_barat'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_batas_timur')) {
            if ($request->old_4) {
                Storage::delete($request->old_4);
            }
            $ext = $request->upload_batas_timur->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_batas_timur')->storeAs($folder, "upload_batas_timur_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'patok_batas_timur'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_peta_batas')) {
            if ($request->old_5) {
                Storage::delete($request->old_5);
            }
            $ext = $request->upload_peta_batas->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kewilayahan";
            $file = $request->file('upload_peta_batas')->storeAs($folder, "upload_peta_batas_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunwil::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'peta_batas'
            ])->update([
                'file_data' => $file
            ]);
        }

        return back()->with('update', 'data berhasil update');
    }

    public function copyAkunwil(Request $request)
    {

        $datatuju = Akunwil::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,

            ]
        )->count();

        if ($request->timpadata) {
            Akunwil::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy

                ]
            )->delete();

            $datas = Akunwil::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunwil::create($data->toArray());
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

        $datas = Akunwil::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunwil::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
