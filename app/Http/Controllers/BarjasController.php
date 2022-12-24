<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Barjas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarjasController extends Controller
{

    public function index(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $tpk = Barjas::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'bac',
        ])->orWhere([
            'nama_data' => 'sk'
        ])->get();

        $survey = Barjas::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'survey',
        ])->get();

        return view('adminDesa.barjas.index', [
            'infos' => $infos,
            'tahun' => $tahun,
            'tpks' => $tpk,
            'surveys' => $survey,
        ]);
    }

    public function tambahtpk(Request $request)
    {
        $request->validate([
            'upload_bac' => 'mimes:pdf|max:1024',
            'upload_sk' => 'mimes:pdf|max:1024',
        ]);
        foreach ($request->nama_data as $nm) {
            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $nm,
            ];
            Barjas::create($data);
        }
        if ($request->file('upload_bac')) {
            $ext = $request->upload_bac->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_barjas";
            $file = $request->file('upload_bac')->storeAs($folder, "upload_bac_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Barjas::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'bac',
            ])->update([
                'file_data' => $file,
            ]);
        }
        if ($request->file('upload_sk')) {
            $ext = $request->upload_sk->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_barjas";
            $file = $request->file('upload_sk')->storeAs($folder, "upload_sk_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Barjas::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sk',
            ])->update([
                'file_data' => $file,
            ]);
        }

        return back();
    }

    public function edittpk(Request $request)
    {
        $request->validate([
            'upload_bac' => 'mimes:pdf|max:1024',
            'upload_sk' => 'mimes:pdf|max:1024',
        ]);
        if ($request->file('upload_bac')) {
            if ($request->old_0 && strpos($request->old_0, $request->tahun)) {
                Storage::delete($request->old_0);
            }
            $ext = $request->upload_bac->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_barjas";
            $file = $request->file('upload_bac')->storeAs($folder, "upload_bac_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Barjas::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'bac'
            ])->update([
                'file_data' => $file
            ]);
        }
        if ($request->file('upload_sk')) {
            if ($request->old_1 && strpos($request->old_1, $request->tahun)) {
                Storage::delete($request->old_1);
            }
            $ext = $request->upload_sk->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_barjas";
            $file = $request->file('upload_sk')->storeAs($folder, "upload_sk_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Barjas::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'sk'
            ])->update([
                'file_data' => $file
            ]);
        }
        return back();
    }

    public function tambahsurvey(Request $request)
    {
        $request->validate([
            'upload_survey' => 'mimes:pdf|max:2048',
        ]);
        foreach ($request->nama_data as $nm) {
            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => $nm,
            ];
            Barjas::create($data);
        }
        if ($request->file('upload_survey')) {
            $ext = $request->upload_survey->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_barjas";
            $file = $request->file('upload_survey')->storeAs($folder, "upload_survey_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Barjas::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'survey',
            ])->update([
                'file_data' => $file,
            ]);
        }

        return back();
    }
    public function editsurvey(Request $request)
    {
        $request->validate([
            'upload_survey' => 'mimes:pdf|max:2048',
        ]);
        if ($request->file('upload_survey')) {
            if ($request->old_0 && strpos($request->old_0, $request->tahun)) {
                Storage::delete($request->old_0);
            }
            $ext = $request->upload_survey->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_barjas";
            $file = $request->file('upload_survey')->storeAs($folder, "upload_survey_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            Barjas::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'nama_data' => 'survey'
            ])->update([
                'file_data' => $file
            ]);
        }
        return back();
    }

    public function copybarjas(Request $request)
    {

        $datatuju = Barjas::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Barjas::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Barjas::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Barjas::create($data->toArray());
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

        $datas = Barjas::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Barjas::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
