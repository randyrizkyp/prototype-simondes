<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Asal;
use App\Models\Galeri;
use App\Models\Datum;
use App\Models\Akunwil;

class ProfilController extends Controller
{
    public function index()
    {
        $data['kecamatans'] = Asal::where('kecamatan', '!=', '')
                ->orderBy('kecamatan')
                ->distinct()
                ->get('kecamatan');

        $data['desas'] = Asal::where('kecamatan', '!=', '')
                ->orderBy('asal')
                ->get();
        
        return view('beranda.profil.profil', $data);
    }

    public function lihatprofil(Request $request)
    {
        $galeri = Galeri::all();
        $data['desas'] =  Asal::where([
            'id' => $request->desa
        ])->get();
        $data['tahun'] = $request->tahun;

        $data['akunwils'] = Akunwil::where([
            'asal_id' => $request->desa,
            'tahun' => $request->tahun
        ])->get();

        $data['datums'] = Datum::where([
            'asal_id' => $request->desa,
            'tahun' => $request->tahun
        ])->get();

        //Sarana Pendidikan
        $pendidikan = Datum::where([
            'asal_id' => $request->desa,
            'tahun' => $request->tahun
        ])->whereIn(
            'nama_data', array('tk', 'sd', 'smp', 'sma', 'ponpes')
        )->get('isidata');
        $jml = 0;
        foreach($pendidikan as $pdd){
            $jml += $pdd->isidata;
        }
        $data['jml_pdd'] = $jml;

        //Sarana Pendidikan
        $ibadah = Datum::where([
            'asal_id' => $request->desa,
            'tahun' => $request->tahun
        ])->whereIn(
            'nama_data', array('mesjid', 'mushola', 'gereja', 'pura', 'vihara', 'klenteng')
        )->get('isidata');
        $jml = 0;
        foreach($ibadah as $ibd){
            $jml += $ibd->isidata;
        }
        $data['jml_ibd'] = $jml;

        //Sarana Kesehatan
        $kesehatan = Datum::where([
            'asal_id' => $request->desa,
            'tahun' => $request->tahun
        ])->whereIn(
            'nama_data', array('puskesmas', 'pustu', 'poskesdes', 'posyandu', 'polindes')
        )->get('isidata');
        $jml = 0;
        foreach($kesehatan as $ksh){
            $jml += $ksh->isidata;
        }
        $data['jml_ksh'] = $jml;

        //Sarana UMUM
        $umum = Datum::where([
            'asal_id' => $request->desa,
            'tahun' => $request->tahun
        ])->whereIn(
            'nama_data', array('olahraga', 'kesenian', 'balai', 'sumur', 'pasar', 'lainnya')
        )->get('isidata');
        $jml = 0;
        foreach($umum as $um){
            $jml += $um->isidata;
        }
        $data['jml_um'] = $jml;

        

        $data['galeris'] = $galeri;
        
        return view('beranda.profil.viewprofil', $data);
    }

    public function getDesa(Request $request)
    {
        $kecamatan = $request->post('kecamatan');
        $desa = Asal::where('kecamatan', $kecamatan)->orderBy('asal')->get();
        $html = '<option value="">---Pilih Desa---</option>';
        foreach ($desa as $ds) {
            $html .= '<option value="' . $ds->id . '">' . $ds->asal . '</option>';
        }
        echo $html;
    }

}
