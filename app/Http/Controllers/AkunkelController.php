<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Akunkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkunkelController extends Controller
{
    public function formAkunkel(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();

        $dataAkunkel = Akunkel::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get()->count();

        if ($dataAkunkel == 0) {
            return view('adminDesa.akunkel.form_akunkel', [
                'tahun' => $tahun,
                'infos' => $infos,


            ]);
        } else {
            return view('adminDesa.akunkel.form_akunkel_e', [
                'tahun' => $tahun,
                'infos' => $infos,
                'dataAkun' => Akunkel::where([
                    'asal_id' => $infos->asal_id,
                    'tahun' => $tahun
                ])->get()

            ]);
        }
    }

    public function tambahAkunkel(Request $request)
    {
        $request->validate([
            'upload_sotk' => 'mimes:pdf|max:1024',
            'upload_sklpm' => 'mimes:pdf|max:1024',
            'upload_sktaruna' => 'mimes:pdf|max:1024',
            'upload_linmas' => 'mimes:pdf|max:1024',
            'upload_kantor_desa' => 'mimes:jpg,png,jpeg,gif|max:200',
            'upload_kg_kdes' => 'mimes:jpg,png,jpeg,gif|max:200',
            'upload_kantor_bpd' => 'mimes:jpg,png,jpeg,gif|max:200',
            'upload_kantor_lpm' => 'mimes:jpg,png,jpeg,gif|max:200',
        ]);
        foreach ($request->nama_data as $nm) {
            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $nm,
            ];
            Akunkel::create($data);
        }
        if ($request->file('upload_sotk')) {
            $ext = $request->upload_sotk->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_sotk')->storeAs($folder, "upload_sotk_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sotk',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_sklpm')) {
            $ext = $request->upload_sklpm->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_sklpm')->storeAs($folder, "upload_sklpm_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sklpm',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_sktaruna')) {
            $ext = $request->upload_sktaruna->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_sktaruna')->storeAs($folder, "upload_sktaruna_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sktaruna',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_linmas')) {
            $ext = $request->upload_linmas->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_linmas')->storeAs($folder, "upload_linmas_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sklinmas',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_kantor_desa')) {
            $ext = $request->upload_kantor_desa->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kantor_desa')->storeAs($folder, "upload_kantor_desa_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kantor_desa',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_kg_kdes')) {
            $ext = $request->upload_kg_kdes->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kg_kdes')->storeAs($folder, "upload_kg_kdes_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kg_kdes',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_kantor_bpd')) {
            $ext = $request->upload_kantor_bpd->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kantor_bpd')->storeAs($folder, "upload_kantor_bpd_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kantor_bpd',

            ])->update([
                'file_data' => $file,

            ]);
        }

        if ($request->file('upload_kantor_lpm')) {
            $ext = $request->upload_kantor_lpm->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kantor_lpm')->storeAs($folder, "upload_kantor_lpm_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kantor_lpm',

            ])->update([
                'file_data' => $file,

            ]);
        }
        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function updateAkunkel(Request $request)
    {
        $request->validate([
            'upload_sotk' => 'mimes:pdf|max:1024',
            'upload_sklpm' => 'mimes:pdf|max:1024',
            'upload_sktaruna' => 'mimes:pdf|max:1024',
            'upload_linmas' => 'mimes:pdf|max:1024',
            'upload_kantor_desa' => 'mimes:jpg,png,jpeg,gif|max:200',
            'upload_kg_kdes' => 'mimes:jpg,png,jpeg,gif|max:200',
            'upload_kantor_bpd' => 'mimes:jpg,png,jpeg,gif|max:200',
            'upload_kantor_lpm' => 'mimes:jpg,png,jpeg,gif|max:200',
        ]);
        if ($request->file('upload_sotk')) {
            if ($request->old_0 && strpos($request->old_0, $request->tahun)) {
                Storage::delete($request->old_0);
            }
            $ext = $request->upload_sotk->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_sotk')->storeAs($folder, "upload_sotk_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sotk'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_sklpm')) {
            if ($request->old_1 && strpos($request->old_1, $request->tahun)) {
                Storage::delete($request->old_1);
            }
            $ext = $request->upload_sklpm->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_sklpm')->storeAs($folder, "upload_sklpm_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sklpm'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_sktaruna')) {
            if ($request->old_2 && strpos($request->old_2, $request->tahun)) {
                Storage::delete($request->old_2);
            }
            $ext = $request->upload_sktaruna->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_sktaruna')->storeAs($folder, "upload_sktaruna_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sktaruna'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_linmas')) {
            if ($request->old_3 && strpos($request->old_3, $request->tahun)) {
                Storage::delete($request->old_3);
            }
            $ext = $request->upload_linmas->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_linmas')->storeAs($folder, "upload_linmas_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sklinmas'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_kantor_desa')) {
            if ($request->old_4 && strpos($request->old_4, $request->tahun)) {
                Storage::delete($request->old_4);
            }
            $ext = $request->upload_kantor_desa->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kantor_desa')->storeAs($folder, "upload_kantor_desa_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kantor_desa'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_kg_kdes')) {
            if ($request->old_5 && strpos($request->old_5, $request->tahun)) {
                Storage::delete($request->old_5);
            }
            $ext = $request->upload_kg_kdes->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kg_kdes')->storeAs($folder, "upload_kg_kdes_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kg_kdes'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_kantor_bpd')) {
            if ($request->old_6 && strpos($request->old_6, $request->tahun)) {
                Storage::delete($request->old_6);
            }
            $ext = $request->upload_kantor_bpd->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kantor_bpd')->storeAs($folder, "upload_kantor_bpd_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kantor_bpd'
            ])->update([
                'file_data' => $file
            ]);
        }

        if ($request->file('upload_kantor_lpm')) {
            if ($request->old_7 && strpos($request->old_7, $request->tahun)) {
                Storage::delete($request->old_7);
            }
            $ext = $request->upload_kantor_lpm->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_kelembagaan";
            $file = $request->file('upload_kantor_lpm')->storeAs($folder, "upload_kantor_lpm_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Akunkel::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'kantor_lpm'
            ])->update([
                'file_data' => $file
            ]);
        }




        return back()->with('update', 'data berhasil update');
    }

    public function copyAkunkel(Request $request)
    {

        $datatuju = Akunkel::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunkel::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunkel::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunkel::create($data->toArray());
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

        $datas = Akunkel::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunkel::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
