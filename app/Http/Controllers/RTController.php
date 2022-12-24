<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Datum_kewilayahan;
use App\Models\Datum_dusun;
use App\Models\Datum;
use App\Models\Datum_perangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RTController extends Controller
{
    public function formRT(Request $request)
    {
        $tahun = now()->format('Y');
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $dawil = Datum::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'jenis' => 'kewilayahan',
            'nama_data' => 'jumlah_rt'
        ])->first();


        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $daper = Datum_dusun::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        if (!$dawil) {
            $jumrt = 0;
        } else {
            $jumrt = $dawil->isidata;
        }


        $rt = "";
        for ($i = 1; $i <= $jumrt; $i++) {

            $rt = $rt . "RT_" . $i . "|";
        }
        substr($rt, 0, -1);
        $rt = explode("|", $rt);
        $data = [];
        for ($i = 1; $i <= $jumrt; $i++) {

            $data[] = Datum_dusun::where([
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun,
                'jabatan' => 'RT_' . $i
            ])->count();
        }



        if (isset($request->jabatan)) {
            $datum = Datum_dusun::where([
                'jabatan' => $request->jabatan,
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun
            ])->get()->count();
            if ($datum == 0) {
                return view('adminDesa.formDatum.rt_t', [
                    'infos' => $infos,
                    'jabatan' => $request->jabatan,
                    'tahun' => $tahun,
                    'rts' => $data


                ]);
            } else {
                return view('adminDesa.formDatum.rt_e', [
                    'infos' => $infos,
                    'jabatan' => $request->jabatan,
                    'tahun' => $tahun,
                    'data' => Datum_dusun::where([
                        'jabatan' => $request->jabatan,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->first(),
                    'rts' => $data

                ]);
            }
        } else {
            return view('adminDesa.formDatum.rt', [
                'infos' => $infos,
                'jabatan' => $request->jabatan,
                'tahun' => $tahun,
                'rts' => $data

            ]);
        }
    }




    public function tambahDatumRT(Request $request)
    {

        $valid = $request->validate([
            'asal_id' => 'required',
            'tahun' => 'required',
            'jabatan' => 'required',
            'status_jab' => 'required',
            'nama' => 'required|max:50',
            'tempat_lahir' => 'required|max:100',
            'tgl_lahir' => 'required',
            'jenkel' => 'required',
            'agama' => 'max:50',
            'nomor_sk' => 'required|max:100',
            'sejak' => 'required',
            'sampai' => 'max:50',
            'pendidikan' => 'required',
            'foto_perangkat' => 'image|file|max:1024',
            'file_sk' => 'mimes:pdf|file|max:1024',
            'file_ijazah' => 'mimes:pdf|file|max:1024'

        ]);


        if ($request->file('foto_perangkat')) {
            $valid['foto_perangkat'] = $request->file('foto_perangkat')->store('foto_perangkat');
        }
        if ($request->file('file_sk')) {
            $valid['file_sk'] = $request->file('file_sk')->store('file_sk');
        }
        if ($request->file('file_ijazah')) {
            $valid['file_ijazah'] = $request->file('file_ijazah')->store('file_ijazah');
        }


        Datum_dusun::create($valid);
        return redirect()->back()->with('success', 'berhasil kirim data perangkat');
    }

    public function updateDatumRT(Request $request)
    {
        $valid = $request->validate([
            'asal_id' => 'required',
            'tahun' => 'required',
            'jabatan' => 'required',
            'status_jab' => 'required',
            'nama' => 'required|max:50',
            'tempat_lahir' => 'required|max:100',
            'tgl_lahir' => 'required',
            'jenkel' => 'required',
            'agama' => 'max:50',
            'nomor_sk' => 'required|max:100',
            'sejak' => 'required',
            'sampai' => 'max:50',
            'pendidikan' => 'required',
            'foto_perangkat' => 'image|file|max:1024',
            'file_sk' => 'mimes:pdf|file|max:1024',
            'file_ijazah' => 'mimes:pdf|file|max:1024'

        ]);

        $data = [
            'asal_id' => strip_tags($request->asal_id),
            'tahun' => strip_tags($request->tahun),
            'jabatan' => strip_tags($request->jabatan),
            'status_jab' => strip_tags($request->status_jab),
            'nama' => strip_tags($request->nama),
            'tempat_lahir' => strip_tags($request->tempat_lahir),
            'tgl_lahir' => strip_tags($request->tgl_lahir),
            'jenkel' => strip_tags($request->jenkel),
            'agama' => strip_tags($request->agama),
            'nomor_sk' => strip_tags($request->nomor_sk),
            'sejak' => strip_tags($request->sejak),
            'sampai' => strip_tags($request->sampai),
            'pendidikan' => strip_tags($request->pendidikan)
        ];

        if ($request->file('foto_perangkat')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }

            $data['foto_perangkat'] = $request->file('foto_perangkat')->store('foto_perangkat');
        }
        if ($request->file('file_sk')) {
            Storage::delete($request->oldSk);
            $data['file_sk'] = $request->file('file_sk')->store('file_sk');
        }
        if ($request->file('file_ijazah')) {
            Storage::delete($request->oldIjazah);
            $data['file_ijazah'] = $request->file('file_ijazah')->store('file_ijazah');
        }

        Datum_dusun::where('id', $request->id)->update($data);
        return redirect("/adminDesa/formRT?jabatan=$request->jabatan&tahun=$request->tahun")->with('success', 'berhasil update data');
    }

    public function copyDatumRT(Request $request)
    {

        $datatuju = Datum_dusun::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
                'jabatan' => $request->jabatan
            ]
        )->first();
        if ($request->timpadata) {
            $data = Datum_dusun::where('id', $request->id)->first();
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';

            $timpa = Datum_dusun::create($data->toArray());
            Datum_dusun::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy,
                    'jabatan' => $request->jabatan
                ]
            )->first()->delete();


            if ($timpa) {
                return redirect()->back()->with('success', 'Data berhasil di timpa');
            }
            exit();
            die();
        }


        if ($datatuju) {
            return back()->with('timpa', $request->tahuncopy);
            exit();
            die();
        }

        $data = Datum_dusun::where('id', $request->id)->first();
        $data['tahun'] = $request->tahuncopy;
        $data['id'] = '';

        // return $data->toArray();

        $copydata = Datum_dusun::create($data->toArray());
        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    public function copyDatumRTAll(Request $request)
    {

        $datatuju = Datum_dusun::where('jabatan', 'like', '%RT%')->where([
            'tahun' => $request->tahuncopy,
            'asal_id' => $request->asal_id
        ])->count();



        if ($request->timpadata) {
            Datum_dusun::where('jabatan', 'like', '%RT%')->where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy

                ]
            )->delete();

            $datas = Datum_dusun::where('jabatan', 'like', '%RT%')->where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Datum_dusun::create($data->toArray());
            }

            if ($copydata) {
                return redirect()->back()->with('success', 'Data berhasil di copy');
            }

            exit();
            die();
        }

        if ($datatuju > 0) {
            return back()->with('timpaRT', $request->tahuncopy);
            exit();
            die();
        }

        $datas = Datum_dusun::where('jabatan', 'like', '%RT%')->where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();


        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Datum_dusun::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
