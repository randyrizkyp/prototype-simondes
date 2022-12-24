<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Rpjmd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RpjmdController extends Controller
{
    public function formRpjmd(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();

        $RpjmdAll = Rpjmd::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        $dokumen = $RpjmdAll->where('jenis', 'dokumen');
        $visimisi = $RpjmdAll->where('jenis', 'visi_misi');
        $potensi = $RpjmdAll->where('jenis', 'potensi');


        if (isset($request->jenis)) {
            $Rpjmd = Rpjmd::where([
                'jenis' => $request->jenis,
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun
            ])->get()->count();
            if ($Rpjmd == 0) {
                return view('adminDesa.formDokren.rpjmd_t', [
                    'infos' => $infos,
                    'jenis' => $request->jenis,
                    'tahun' => $tahun,
                    'dokumen' => count($dokumen),
                    'visimisi' => count($visimisi),

                    'potensi' => count($potensi),

                ]);
            } else {
                return view('adminDesa.formDokren.rpjmd_e', [
                    'infos' => $infos,
                    'jenis' => $request->jenis,
                    'tahun' => $tahun,
                    'dokumen' => count($dokumen),
                    'visimisi' => count($visimisi),
                    'potensi' => count($potensi),
                    'data' => Rpjmd::where([
                        'jenis' => $request->jenis,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->get(),
                    'visi' => Rpjmd::where([
                        'jenis' => $request->jenis,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun,
                        'nama_data' => 'visi'
                    ])->first(),
                    'misis' => Rpjmd::where([
                        'jenis' => $request->jenis,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun,
                    ])->where('nama_data', 'like', '%' . 'misi' . '%')->get(),
                    'jumlah_misi' => Rpjmd::where([
                        'jenis' => $request->jenis,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun,
                    ])->where('nama_data', 'like', '%' . 'misi' . '%')->get()->count()

                ]);
            }
        } else {
            return view('adminDesa.formDokren.rpjmd', [
                'infos' => $infos,
                'jenis' => $request->jenis,
                'tahun' => $tahun,
                'dokumen' => count($dokumen),
                'visimisi' => count($visimisi),
                'potensi' => count($potensi)


            ]);
        }
    }

    public function tambahRpjmd(Request $request)
    {
        $validate = $request->validate([
            'dokumen_rpjmd' => 'file|mimes:pdf|max:30720'

        ]);
        $i = 0;
        foreach ($request->nama_data as $data) {

            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'jenis' => $request->jenis,
                'nama_data' => $data,
                'uraian' => strip_tags($request->isidata[$i]),
                'sejak' => strip_tags($request->sejak),
                'sampai' => strip_tags($request->sampai)
            ];

            Rpjmd::create($data);
            $i++;
        }
        if ($request->file('dokumen_rpjmd')) {
            $ext = $request->dokumen_rpjmd->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_dokren";
            $file = $request->file('dokumen_rpjmd')->storeAs($folder, "dokumen_rpjmd." . $ext);
            Rpjmd::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'nama_data' => 'dokumen_rpjmd'
            ])->update([
                'file_data' => $file
            ]);
        }

        return back()->with('success', 'berhasil tambah data');
    }
    public function updateRpjmd(Request $request)
    {
        $validate = $request->validate([
            'dokumen_rpjmd' => 'file|mimes:pdf|max:30720'

        ]);
        $i = 0;
        foreach ($request->nama_data as $data) {

            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'jenis' => $request->jenis,
                'nama_data' => $data

            ];

            Rpjmd::Where($data)->update([
                'uraian' => strip_tags($request->isidata[$i]),
                'sejak' => strip_tags($request->sejak),
                'sampai' => strip_tags($request->sampai)
            ]);
            $i++;
        }
        if ($request->file('dokumen_rpjmd')) {
            if ($request->old_1) {
                Storage::delete($request->old_1);
            }
            $ext = $request->dokumen_rpjmd->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_dokren";
            $file = $request->file('dokumen_rpjmd')->storeAs($folder, "dokumen_rpjmd_" . $request->tahun . "-" . mt_rand(1, 20) . "." . $ext);
            Rpjmd::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'nama_data' => 'dokumen_rpjmd'
            ])->update([
                'file_data' => $file
            ]);
        }

        return back()->with('success', 'berhasil Update data');
    }

    public function tambahVisimisi(Request $request)
    {
        $i = 0;
        foreach ($request->nama_data as $data) {

            $data = [
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'jenis' => $request->jenis,
                'nama_data' => $data,
                'uraian' => strip_tags($request->isidata[$i])

            ];

            Rpjmd::create($data);
            $i++;
        }


        return back()->with('success', 'berhasil tambah data');
    }

    public function deleteVisimisi($id)
    {
        Rpjmd::destroy($id);
        return back()->with('success', 'data berhasil dihapus');
    }
}
