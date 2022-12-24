<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Asal;


class DataperangkatController extends Controller
{
    public function index()
    {
        $data['kecamatans'] = DB::table('asals')
            ->distinct()
            ->where('kecamatan', '!=', '')
            ->get('kecamatan');

        $data['desas'] = DB::table('asals')
            ->where('kecamatan', '!=', '')
            ->get();

        return view('beranda.dataperangkat.dataperangkat', $data);
    }

    public function lihatdataperangkat(Request $request)
    {
        $data['desas'] =  DB::table('asals')
            ->where('id', $request->desa)
            ->get();
        return view('beranda.dataperangkat.viewdataperangkat', $data);
    }

    public function getDesa(Request $request)
    {
        $kecamatan = $request->post('kecamatan');
        $desa = DB::table('asals')->where('kecamatan', $kecamatan)->get();
        $html = '<option value="">---Pilih Desa---</option>';
        foreach ($desa as $ds) {
            $html .= '<option value="' . $ds->id . '">' . $ds->asal . '</option>';
        }
        echo $html;
    }

}
