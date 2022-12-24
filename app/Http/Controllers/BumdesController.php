<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Asal;


class BumdesController extends Controller
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

    public function lihatbumdes(Request $request)
    {
        $data['desas'] =  DB::table('asals')
            ->where('id', $request->desa)
            ->get();
        return view('beranda.bumdes.viewbumdes', $data);
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
