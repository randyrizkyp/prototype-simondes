<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\Admin;

use Illuminate\Http\Request;

class DatumController extends Controller
{
    public function formKewilayahan(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();

        $datumAll = Datum::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        $kewilayahan = $datumAll->where('jenis', 'kewilayahan');
        $kependudukan = $datumAll->where('jenis', 'kependudukan');
        $sarpras = $datumAll->where('jenis', 'sarpras');
        $kelembagaan = $datumAll->where('jenis', 'kelembagaan');


        if (isset($request->jenis)) {
            $datum = Datum::where([
                'jenis' => $request->jenis,
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun
            ])->get()->count();
            if ($datum == 0) {
                return view('adminDesa.formDatum.kewilayahan_t', [
                    'infos' => $infos,
                    'jenis' => $request->jenis,
                    'tahun' => $tahun,
                    'kewilayahan' => count($kewilayahan),
                    'kependudukan' => count($kependudukan),
                    'sarpras' => count($sarpras),
                    'kelembagaan' => count($kelembagaan),

                ]);
            } else {
                return view('adminDesa.formDatum.kewilayahan_e', [
                    'infos' => $infos,
                    'jenis' => $request->jenis,
                    'tahun' => $tahun,
                    'kewilayahan' => count($kewilayahan),
                    'kependudukan' => count($kependudukan),
                    'sarpras' => count($sarpras),
                    'kelembagaan' => count($kelembagaan),
                    'data' => Datum::where([
                        'jenis' => $request->jenis,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->get()

                ]);
            }
        } else {
            return view('adminDesa.formDatum.kewilayahan', [
                'infos' => $infos,
                'jenis' => $request->jenis,
                'tahun' => $tahun,
                'kewilayahan' => count($kewilayahan),
                'kependudukan' => count($kependudukan),
                'sarpras' => count($sarpras),
                'kelembagaan' => count($kelembagaan)


            ]);
        }
    }

    public function tambahDatumWil(Request $request)
    {


        for ($i = 0; $i < 8; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $insert = Datum::create($data);
        }

        if ($insert) {
            return back()->with('success', 'berhasil kirim data');
        }
    }

    public function updateDatumWil(Request $request)
    {



        for ($i = 0; $i < 8; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $update = Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'nama_data' => $request->nama_data[$i]
            ])->update($data);
        }

        if ($update) {
            return back()->with('update', 'berhasil update data');
        }
    }

    public function tambahDatumDuk(Request $request)
    {
        for ($i = 0; $i < 10; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $insert = Datum::create($data);
        }

        if ($insert) {
            return back()->with('success', 'berhasil kirim data');
        }
    }

    public function updateDatumDuk(Request $request)
    {



        for ($i = 0; $i < 10; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $update = Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'nama_data' => $request->nama_data[$i]
            ])->update($data);
        }

        if ($update) {
            return back()->with('update', 'berhasil update data');
        }
    }

    public function tambahDatumPras(Request $request)
    {

        for ($i = 0; $i < 22; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $insert = Datum::create($data);
        }

        if ($insert) {
            return back()->with('success', 'berhasil kirim data');
        }
    }

    public function updateDatumPras(Request $request)
    {

        for ($i = 0; $i < 22; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i]
            ])->update([
                'isidata' => $request->isidata[$i]
            ]);
        }


        return back()->with('update', 'berhasil update data');
    }


    // Copy Datum
    // copy Datum All
    public function copyDatum(Request $request)
    {

        $datatuju = Datum::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
                'jenis' => $request->jenis

            ]
        )->count();

        if ($request->timpadata) {
            Datum::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy,
                    'jenis' => $request->jenis

                ]
            )->delete();

            $datas = Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal,
                'jenis' => $request->jenis
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Datum::create($data->toArray());
            }

            if ($copydata) {
                return redirect()->back()->with('success', 'Data berhasil di copy');
            }

            exit();
            die();
        }

        if ($datatuju > 0) {
            return back()->with('timpa', $request->tahuncopy);
            exit();
            die();
        }

        $datas = Datum::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal,
            'jenis' => $request->jenis
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Datum::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }


    // copy Datum All
    public function copyDatumAll(Request $request)
    {

        $datatuju = Datum::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy

            ]
        )->count();

        if ($request->timpadata) {
            Datum::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy

                ]
            )->delete();

            $datas = Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Datum::create($data->toArray());
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

        $datas = Datum::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Datum::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    // tambah datum kelembagaan
    public function tambahDatumLembaga(Request $request)
    {

        for ($i = 0; $i < 6; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $insert = Datum::create($data);
        }

        if ($insert) {
            return back()->with('success', 'berhasil kirim data');
        }
    }

    public function updateDatumLembaga(Request $request)
    {

        for ($i = 0; $i < 6; $i++) {

            Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i]
            ])->update([
                'isidata' => $request->isidata[$i]
            ]);
        }


        return back()->with('update', 'berhasil update data');
    }

    //KELEMBAGAAN
    public function formKelembagaan(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();

        $datumAll = Datum::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        $kewilayahan = $datumAll->where('jenis', 'kewilayahan');
        $kependudukan = $datumAll->where('jenis', 'kependudukan');
        $sarpras = $datumAll->where('jenis', 'sarpras');
        $kelembagaan = $datumAll->where('jenis', 'kelembagaan');


        if (isset($request->jenis)) {
            $datum = Datum::where([
                'jenis' => $request->jenis,
                'asal_id' => $infos->asal_id,
                'tahun' => $tahun
            ])->get()->count();
            if ($datum == 0) {
                return view('adminDesa.formDatum.kelembagaan_t', [
                    'infos' => $infos,
                    'jenis' => $request->jenis,
                    'tahun' => $tahun,
                    'kewilayahan' => count($kewilayahan),
                    'kependudukan' => count($kependudukan),
                    'sarpras' => count($sarpras),
                    'kelembagaan' => count($kelembagaan),

                ]);
            } else {
                return view('adminDesa.formDatum.kelembagaan_e', [
                    'infos' => $infos,
                    'jenis' => $request->jenis,
                    'tahun' => $tahun,
                    'kewilayahan' => count($kewilayahan),
                    'kependudukan' => count($kependudukan),
                    'sarpras' => count($sarpras),
                    'kelembagaan' => count($kelembagaan),
                    'data' => Datum::where([
                        'jenis' => $request->jenis,
                        'asal_id' => $infos->asal_id,
                        'tahun' => $tahun
                    ])->get()

                ]);
            }
        } else {
            return view('adminDesa.formDatum.kelembagaan', [
                'infos' => $infos,
                'jenis' => $request->jenis,
                'tahun' => $tahun,
                'kewilayahan' => count($kewilayahan),
                'kependudukan' => count($kependudukan),
                'sarpras' => count($sarpras),
                'kelembagaan' => count($kelembagaan)


            ]);
        }
    }

    public function tambahDatumKel(Request $request)
    {


        for ($i = 0; $i < 8; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $insert = Datum::create($data);
        }

        if ($insert) {
            return back()->with('success', 'berhasil kirim data');
        }
    }

    public function updateDatumKel(Request $request)
    {



        for ($i = 0; $i < 8; $i++) {
            $data = [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'jenis' => $request->jenis,
                'nama_data' => $request->nama_data[$i],
                'isidata' => strip_tags($request->isidata[$i])
            ];
            $update = Datum::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahun,
                'nama_data' => $request->nama_data[$i]
            ])->update($data);
        }

        if ($update) {
            return back()->with('update', 'berhasil update data');
        }
    }
}
