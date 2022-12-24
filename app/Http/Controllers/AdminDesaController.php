<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Datum_kewilayahan;
use App\Models\Datum_perangkat;

class AdminDesaController extends Controller
{

    public function index()
    {
        $adminInfo = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();

        return view('adminDesa.index', [
            'infos' => $adminInfo,

        ]);
    }

    public function logout(Request $request)
    {
        if (session()->has('loggedAdminDesa')) {
            session()->pull('loggedAdminDesa');
            session()->pull('loggedAdmin');
            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/');
        }
    }

    public function formKewilayahan()
    {
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $datum = Datum_kewilayahan::where('asal_id', session('loggedAdminDesa'))->get()->count();


        if ($datum == 0) {
            return view('adminDesa.formDatum.kewilayahan', [
                'infos' => $infos

            ]);
        } else {
            return view('adminDesa.formDatum.kewilayahan_e', [
                'infos' => $infos,
                'wilayahs' => Datum_kewilayahan::where('asal_id', session('loggedAdminDesa'))->first()
            ]);
        }
    }

    public function tambahDatumWil(Request $request)
    {
        $data = $request->validate([
            'dasar_hukum' => 'max:255',
            'luas' => 'max:10',
            'asal_id' => 'required',
            'batas_utara' => 'required',
            'batas_selatan' => 'required',
            'batas_barat' => 'required',
            'batas_timur' => 'required',
            'jumlah_dusun' => 'required',
            'jumlah_rt' => 'required',
            'jumlah_bpd' => 'required'

        ]);
        $data = [
            'dasar_hukum' => strip_tags($request->dasar_hukum),
            'luas' => strip_tags($request->luas),
            'asal_id' => strip_tags($request->asal_id),
            'batas_utara' => strip_tags($request->batas_utara),
            'batas_selatan' => strip_tags($request->batas_selatan),
            'batas_barat' => strip_tags($request->batas_barat),
            'batas_timur' => strip_tags($request->batas_timur),
            'jumlah_dusun' => strip_tags($request->jumlah_dusun),
            'jumlah_rt' => strip_tags($request->jumlah_rt),
            'jumlah_bpd' => strip_tags($request->jumlah_bpd)
        ];
        $tambahwil = Datum_kewilayahan::create($data);
        if ($tambahwil) {
            return redirect('/adminDesa/formKewilayahan')->with('tambah', 'Berhasil input data kewilayahan');
        }
    }

    public function updateDatumWil(Request $request)
    {
        $data = $request->validate([
            'dasar_hukum' => 'max:255',
            'luas' => 'max:10',
            'asal_id' => 'required',
            'batas_utara' => 'required',
            'batas_selatan' => 'required',
            'batas_barat' => 'required',
            'batas_timur' => 'required',
            'jumlah_dusun' => 'required',
            'jumlah_rt' => 'required',
            'jumlah_bpd' => 'required'

        ]);


        $data = [
            'dasar_hukum' => strip_tags($request->dasar_hukum),
            'luas' => strip_tags($request->luas),
            'asal_id' => strip_tags($request->asal_id),
            'batas_utara' => strip_tags($request->batas_utara),
            'batas_selatan' => strip_tags($request->batas_selatan),
            'batas_barat' => strip_tags($request->batas_barat),
            'batas_timur' => strip_tags($request->batas_timur),
            'jumlah_dusun' => strip_tags($request->jumlah_dusun),
            'jumlah_rt' => strip_tags($request->jumlah_rt),
            'jumlah_bpd' => strip_tags($request->jumlah_bpd)
        ];
        $updatewil = Datum_kewilayahan::where('asal_id', $data['asal_id'])->update($data);
        if ($updatewil) {
            return redirect('/adminDesa/formKewilayahan')->with('updated', 'data berhasil diupdate');
        }
    }

    public function formPerangkat()
    {
        $tahun = now()->format('Y');
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $datum = Datum_perangkat::where([
            'asal_id' => session('loggedAdminDesa'),
            'tahun' => $tahun
        ])->get()->count();


        if ($datum == 0) {
            return view('adminDesa.formDatum.perangkat', [
                'infos' => $infos

            ]);
        } else {
            return view('adminDesa.formDatum.perangkat_e', [
                'infos' => $infos,
                'wilayahs' => Datum_kewilayahan::where('asal_id', session('loggedAdminDesa'))->first()
            ]);
        }
    }
    public function tambahDatumPer(Request $request)
    {
        return $request;
    }
}
