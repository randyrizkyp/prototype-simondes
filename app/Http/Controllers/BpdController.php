<?php

namespace App\Http\Controllers;

use App\Models\Bpd;
use App\Models\Admin;
use App\Models\Datum;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class BpdController extends Controller
{
    // Form dan CRUD BPD
    public function formBPD(Request $request)
    {
        $tahun = now()->format('Y');
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $dawil = Datum::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'jenis' => 'kelembagaan',
            'nama_data' => 'jumlah_bpd'
        ])->first();


        if ($request->tahun) {
            $tahun = $request->tahun;
        }


        if (!$dawil) {
            $jumbpd = 0;
        } else {
            $jumbpd = $dawil->isidata;
            $jumbpd = $jumbpd - 3;
        }

        $bpd = "";


        for ($i = 1; $i <= $jumbpd; $i++) {

            $bpd = $bpd . "BPD_" . $i . "|";
        }
        substr($bpd, 0, -1);
        $bpd = explode("|", $bpd);
        $data = [];
        for ($i = 1; $i <= $jumbpd; $i++) {

            $data[] = Bpd::where([
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun,
                'jabatan' => 'anggota_bpd_' . $i
            ])->count();
        }




        if (isset($request->jabatan)) {
            $datum = Bpd::where([
                'jabatan' => $request->jabatan,
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun
            ])->get()->count();
            if ($datum == 0) {
                return view('adminDesa.formDatum.bpd_t', [
                    'infos' => $infos,
                    'jabatan' => $request->jabatan,
                    'tahun' => $tahun,
                    'bpds' => $data,
                    'ketua' => Bpd::where([
                        'jabatan' => 'ketua_bpd',
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->count(),
                    'wakil' => Bpd::where([
                        'jabatan' => 'wakil_ketua_bpd',
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->count(),
                    'sekretaris' => Bpd::where([
                        'jabatan' => 'sekretaris_bpd',
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->count()

                ]);
            } else {
                return view('adminDesa.formDatum.bpd_e', [
                    'infos' => $infos,
                    'jabatan' => $request->jabatan,
                    'tahun' => $tahun,
                    'data' => Bpd::where([
                        'jabatan' => $request->jabatan,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->first(),
                    'bpds' => $data,
                    'ketua' => Bpd::where([
                        'jabatan' => 'ketua_bpd',
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->count(),
                    'wakil' => Bpd::where([
                        'jabatan' => 'wakil_ketua_bpd',
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->count(),
                    'sekretaris' => Bpd::where([
                        'jabatan' => 'sekretaris_bpd',
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->count()

                ]);
            }
        } else {

            return view('adminDesa.formDatum.bpd', [
                'infos' => $infos,
                'jabatan' => $request->jabatan,
                'tahun' => $tahun,
                'bpds' => $data,
                'ketua' => Bpd::where([
                    'jabatan' => 'ketua_bpd',
                    'asal_id' => $infos->asal_id,
                    'tahun' => $tahun
                ])->count(),
                'wakil' => Bpd::where([
                    'jabatan' => 'wakil_ketua_bpd',
                    'asal_id' => $infos->asal_id,
                    'tahun' => $tahun
                ])->count(),
                'sekretaris' => Bpd::where([
                    'jabatan' => 'sekretaris_bpd',
                    'asal_id' => $infos->asal_id,
                    'tahun' => $tahun
                ])->count()


            ]);
        }
    }

    public function tambahDatumBpd(Request $request)
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
            'hp' => 'max:25',
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


        Bpd::create($valid);
        return redirect()->back()->with('success', 'berhasil kirim data perangkat');
    }

    public function updateDatumBpd(Request $request)
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
            'hp' => 'max:25',
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
            'hp' => strip_tags($request->hp),
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

        Bpd::where('id', $request->id)->update($data);
        return back()->with('success', 'berhasil update data');
    }

    public function copyDatumBpdAll(Request $request)
    {

        $datatuju = Bpd::where('jabatan', 'like', '%bpd%')->where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy

            ]
        )->count();

        if ($request->timpadata) {
            Bpd::where('jabatan', 'like', '%bpd%')->where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy

                ]
            )->delete();

            $datas = bpd::where('jabatan', 'like', '%bpd%')->where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = bpd::create($data->toArray());
            }

            if ($copydata) {
                return redirect()->back()->with('success', 'Data berhasil di copy');
            }

            exit();
            die();
        }

        if ($datatuju > 0) {
            return back()->with('timpaBpd', $request->tahuncopy);
            exit();
            die();
        }

        $datas = bpd::where('jabatan', 'like', '%bpd%')->where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = bpd::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    public function copyDatumBpd(Request $request)
    {

        $datatuju = Bpd::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
                'jabatan' => $request->jabatan
            ]
        )->first();
        if ($request->timpadata) {
            $data = Bpd::where('id', $request->id)->first();
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';

            $timpa = Bpd::create($data->toArray());
            Bpd::where(
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

        $data = Bpd::where('id', $request->id)->first();
        $data['tahun'] = $request->tahuncopy;
        $data['id'] = '';

        // return $data->toArray();

        $copydata = Bpd::create($data->toArray());
        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    public function test(Request $request)
    {
        $data = Bpd::Where('asal_id', 1)->get();
        return view('adminDesa.formDokren.test');
    }
}
