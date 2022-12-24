<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Asal;
use App\Models\Akunasetkib;
use App\Models\Akunasetbia;
use App\Models\Akunasetkiba;
use App\Models\Akunasetkir;
use App\Models\Akunasetholder;



class AsetController extends Controller
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
        
        return view('beranda.aset.aset', $data);
    }

    public function lihataset(Request $request)
    {

        $data['desas'] = Asal::where([
            'id' => $request->desa,
        ])->get();
        $data['tahun'] = $request->tahun;
        $data['penggunaan'] = Akunasetbia::where([
            'asal_id'   => $request->desa,
            'tahun'     => $request->tahun
        ])->get();
        $data['kibas'] = Akunasetkiba::where([
            'asal_id'   => $request->desa,
            'tahun'     => $request->tahun
        ])->get();
        $data['kirs'] = Akunasetkir::where([
            'asal_id'   => $request->desa,
            'tahun'     => $request->tahun
        ])->get();
        $data['holders'] = Akunasetholder::where([
            'asal_id'   => $request->desa,
            'tahun'     => $request->tahun
        ])->get();
        
        return view('beranda.aset.viewaset',  $data);
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
