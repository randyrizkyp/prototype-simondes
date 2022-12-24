<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Datum_kewilayahan;
use App\Models\Datum_perangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerangkatController extends Controller
{

    public function formPerangkat(Request $request)
    {
        $tahun = now()->format('Y');
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $daper = Datum_perangkat::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();


        if (isset($request->jabatan)) {
            $datum = Datum_perangkat::where([
                'jabatan' => $request->jabatan,
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun
            ])->get()->count();
            if ($datum == 0) {
                return view('adminDesa.formDatum.perangkat_t', [
                    'infos' => $infos,
                    'jabatan' => $request->jabatan,
                    'tahun' => $tahun,
                    'kades' => $daper->where('jabatan', 'Kepala Desa')->count(),
                    'sekdes' => $daper->where('jabatan', 'Sekretaris Desa')->count(),
                    'kaur_umum' => $daper->where('jabatan', 'Kaur Umum')->count(),
                    'kaur_per' => $daper->where('jabatan', 'Kaur Perencanaan')->count(),
                    'kaur_keu' => $daper->where('jabatan', 'Kaur Keuangan')->count(),
                    'kasi_pem' => $daper->where('jabatan', 'Kasi Pemerintahan')->count(),
                    'kasi_kesra' => $daper->where('jabatan', 'Kasi Kesra')->count(),
                    'kasi_pel' => $daper->where('jabatan', 'Kasi Pelayanan')->count()

                ]);
            } else {
                return view('adminDesa.formDatum.perangkat_e', [
                    'infos' => $infos,
                    'jabatan' => $request->jabatan,
                    'tahun' => $tahun,
                    'data' => Datum_perangkat::where([
                        'jabatan' => $request->jabatan,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->first(),
                    'kades' => $daper->where('jabatan', 'Kepala Desa')->count(),
                    'sekdes' => $daper->where('jabatan', 'Sekretaris Desa')->count(),
                    'kaur_umum' => $daper->where('jabatan', 'Kaur Umum')->count(),
                    'kaur_per' => $daper->where('jabatan', 'Kaur Perencanaan')->count(),
                    'kaur_keu' => $daper->where('jabatan', 'Kaur Keuangan')->count(),
                    'kasi_pem' => $daper->where('jabatan', 'Kasi Pemerintahan')->count(),
                    'kasi_kesra' => $daper->where('jabatan', 'Kasi Kesra')->count(),
                    'kasi_pel' => $daper->where('jabatan', 'Kasi Pelayanan')->count()
                ]);
            }
        } else {
            return view('adminDesa.formDatum.perangkat', [
                'infos' => $infos,
                'jabatan' => $request->jabatan,
                'tahun' => $tahun,
                'kades' => $daper->where('jabatan', 'Kepala Desa')->count(),
                'sekdes' => $daper->where('jabatan', 'Sekretaris Desa')->count(),
                'kaur_umum' => $daper->where('jabatan', 'Kaur Umum')->count(),
                'kaur_per' => $daper->where('jabatan', 'Kaur Perencanaan')->count(),
                'kaur_keu' => $daper->where('jabatan', 'Kaur Keuangan')->count(),
                'kasi_pem' => $daper->where('jabatan', 'Kasi Pemerintahan')->count(),
                'kasi_kesra' => $daper->where('jabatan', 'Kasi Kesra')->count(),
                'kasi_pel' => $daper->where('jabatan', 'Kasi Pelayanan')->count()

            ]);
        }
    }




    public function tambahDatumPer(Request $request)
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


        Datum_perangkat::create($valid);
        return redirect()->back()->with('success', 'berhasil kirim data perangkat');
    }

    public function updateDatumPer(Request $request)
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

        Datum_perangkat::where('id', $request->id)->update($data);
        return redirect("/adminDesa/formPerangkat?jabatan=$request->jabatan&tahun=$request->tahun")->with('success', 'berhasil update data');
    }

    public function copyDatumPer(Request $request)
    {

        $datatuju = Datum_perangkat::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
                'jabatan' => $request->jabatan
            ]
        )->first();
        if ($request->timpadata) {
            $data = Datum_perangkat::where('id', $request->id)->first();
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';

            $timpa = Datum_perangkat::create($data->toArray());
            Datum_perangkat::where(
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

        $data = Datum_perangkat::where('id', $request->id)->first();
        $data['tahun'] = $request->tahuncopy;
        $data['id'] = '';

        // return $data->toArray();

        $copydata = Datum_perangkat::create($data->toArray());
        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
