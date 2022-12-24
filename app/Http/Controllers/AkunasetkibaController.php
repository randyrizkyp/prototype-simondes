<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Akunasetkib;
use App\Models\Akunasetkiba;
use App\Models\Asal;
use App\Models\Datum_perangkat;
use Illuminate\Http\Request;
use App\Models\Admin;
use Maatwebsite\Excel\Facades\Excel;

class AkunasetkibaController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $tahun = now()->format('Y');
        if ($request->tahun) {
            $tahun = $request->tahun;
        }
        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        $Akunasetkiba = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'lhi' => 'tanah'
        ])->get();

        $Akunasetkibb = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'lhi' => 'kendaraan'
        ])->get();

        $Akunasetkibc = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'lhi' => 'mesin'
        ])->get();

        $Akunasetkibd = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'lhi'   => 'bangunan'
        ])->get();

        $Akunasetkibe = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'lhi' => 'jalan'
        ])->get();

        $Akunasetkibf = Akunasetkiba::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'lhi'   => 'lainnya'
        ])->get();

        $kibtanah = Akunasetkib::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'lhi_tanah',
        ])->get('file_data');

        $kibkendaraan = Akunasetkib::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'lhi_kendaraan',
        ])->get('file_data');

        $kibmesin = Akunasetkib::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'lhi_mesin',
        ])->get('file_data');

        $kibbangunan = Akunasetkib::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'lhi_bangunan',
        ])->get('file_data');

        $kibjalan = Akunasetkib::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'lhi_jalan',
        ])->get('file_data');

        $kiblainnya = Akunasetkib::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'nama_data' => 'lhi_lainnya',
        ])->get('file_data');


        return view('adminDesa.aset.kib.index',  [
            'infos' => $infos,
            'Akunasetkibas' => $Akunasetkiba,
            'Akunasetkibbs' => $Akunasetkibb,
            'Akunasetkibcs' => $Akunasetkibc,
            'Akunasetkibds' => $Akunasetkibd,
            'Akunasetkibes' => $Akunasetkibe,
            'Akunasetkibfs' => $Akunasetkibf,
            'kibtanah' => $kibtanah,
            'kibkendaraan' => $kibkendaraan,
            'kibmesin' => $kibmesin,
            'kibbangunan' => $kibbangunan,
            'kibjalan' => $kibjalan,
            'kiblainnya' => $kiblainnya,
            'tahun' => $tahun,

        ]);
    }


    public function store(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        Akunasetkiba::create([
            'asal_id'           => $request->asal_id,
            'tahun'             => $request->tahun,
            'jenis'             => $request->jenis_tanah,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->luas,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->bukti_kepemilikan,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
            'lhi'               => 'tanah'
        ]);

        return back()->with('tambah', 'data berhasil ditambah');
    }



    public function update(Request $request, Akunasetkiba $Akunasetkiba)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        $Akunasetkiba = Akunasetkiba::findOrFail($Akunasetkiba->id);
        $Akunasetkiba->update([
            'jenis'       => $request->jenis_tanah,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->luas,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->bukti_kepemilikan,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
        ]);
        return back()->with('tambah', 'data berhasil diupdate');
    }

    public function addkibb(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        Akunasetkiba::create([
            'asal_id'           => $request->asal_id,
            'tahun'             => $request->tahun,
            'jenis'             => $request->nama_barang,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->type,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->nomor_identitas,
            'kondisi_barang'    => $request->kondisi,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
            'lhi'               => 'kendaraan'
        ]);

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function editkibb(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        $Akunasetkiba = Akunasetkiba::findOrFail($request->id);
        $Akunasetkiba->update([
            'jenis'       => $request->nama_barang,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->type,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->nomor_identitas,
            'kondisi_barang'    => $request->kondisi,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
        ]);
        return back()->with('tambah', 'data berhasil diupdate');
    }

    public function addkibc(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        Akunasetkiba::create([
            'asal_id'           => $request->asal_id,
            'tahun'             => $request->tahun,
            'jenis'             => $request->nama_barang,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->type,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->nomor_identitas,
            'kondisi_barang'    => $request->kondisi,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
            'lhi'               => 'mesin'
        ]);

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function editkibc(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        $Akunasetkiba = Akunasetkiba::findOrFail($request->id);
        $Akunasetkiba->update([
            'jenis'       => $request->nama_barang,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->type,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->nomor_identitas,
            'kondisi_barang'    => $request->kondisi,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
        ]);
        return back()->with('tambah', 'data berhasil diupdate');
    }

    public function addkibd(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        Akunasetkiba::create([
            'asal_id'           => $request->asal_id,
            'tahun'             => $request->tahun,
            'jenis'             => $request->jenis_bangunan,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->luas,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->type,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
            'lhi'               => 'bangunan'
        ]);

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function editkibd(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        $Akunasetkiba = Akunasetkiba::findOrFail($request->id);
        $Akunasetkiba->update([
            'jenis'             => $request->jenis_bangunan,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->luas,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->type,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
        ]);
        return back()->with('tambah', 'data berhasil diupdate');
    }

    public function addkibe(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        Akunasetkiba::create([
            'asal_id'           => $request->asal_id,
            'tahun'             => $request->tahun,
            'jenis'             => $request->jenis_jalan,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->ukuran,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->type,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
            'lhi'               => 'jalan'
        ]);

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function editkibe(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        $Akunasetkiba = Akunasetkiba::findOrFail($request->id);
        $Akunasetkiba->update([
            'jenis'       => $request->jenis_jalan,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->ukuran,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'type_identitas'    => $request->ukuran,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
        ]);
        return back()->with('tambah', 'data berhasil diupdate');
    }
    public function addkibf(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        Akunasetkiba::create([
            'asal_id'           => $request->asal_id,
            'tahun'             => $request->tahun,
            'jenis'             => $request->nama_barang,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->type,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
            'lhi'               => 'lainnya'
        ]);

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function editkibf(Request $request)
    {
        if ($request->nilai_perolehan) {
            $dana = preg_replace('/\D/', '', $request->nilai_perolehan);
        }
        $Akunasetkiba = Akunasetkiba::findOrFail($request->id);
        $Akunasetkiba->update([
            'jenis'       => $request->nama_barang,
            'kd_barang'         => $request->kd_barang,
            'nup'               => $request->nup,
            'identitas'         => $request->type,
            'tahun_perolehan'   => $request->tahun_perolehan,
            'nilai_perolehan'   => $dana,
            'asal'              => $request->asal,
            'ket'               => $request->ket,
        ]);
        return back()->with('tambah', 'data berhasil diupdate');
    }


    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $Akunasetkiba = Akunasetkiba::findOrFail($id);
        $Akunasetkiba->delete();

        return back()->with('hapus', 'data berhasil dihapus');
    }

    public function copyAkunaset(Request $request)
    {

        $datatuju = Akunasetkiba::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunasetkiba::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunasetkiba::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();

            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunasetkiba::create($data->toArray());
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

        $datas = Akunasetkiba::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunasetkiba::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    public function cetak($id, $tahun, $lhi)
    {
        $Akunasetkiba = Akunasetkiba::where([
            'asal_id' => $id,
            'tahun' => $tahun,
            'lhi' => $lhi
        ])->get();

        $Akunasetbia = Akunasetkiba::where([
            'asal_id' => $id,
            'tahun' => $tahun,
        ])->get();

        $desa = Asal::where([
            'id' => $id
        ])->get();

        $kades = Datum_perangkat::where([
            'asal_id' => $id,
            'jabatan' => 'Kepala Desa',
            'tahun' => $tahun
        ])->get();

        $total = 0;
        foreach ($Akunasetkiba as $akun) {
            $total += $akun->nilai_perolehan;
        }

        if ($lhi == 'tanah') {
            return view('adminDesa.aset.kib.laporan_pdf',  [
                'Akunasetkibas' => $Akunasetkiba,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades
            ]);
        } elseif ($lhi == 'kendaraan') {
            return view('adminDesa.aset.kib.laporan_kibb',  [
                'Akunasetkibas' => $Akunasetkiba,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades
            ]);
        } elseif ($lhi == 'mesin') {
            return view('adminDesa.aset.kib.laporan_kibc',  [
                'Akunasetkibas' => $Akunasetkiba,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades
            ]);
        } elseif ($lhi == 'bangunan') {
            return view('adminDesa.aset.kib.laporan_kibd',  [
                'Akunasetkibas' => $Akunasetkiba,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades
            ]);
        } elseif ($lhi == 'jalan') {
            return view('adminDesa.aset.kib.laporan_kibe',  [
                'Akunasetkibas' => $Akunasetkiba,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades
            ]);
        } elseif ($lhi == 'lainnya') {
            return view('adminDesa.aset.kib.laporan_kibf',  [
                'Akunasetkibas' => $Akunasetkiba,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades
            ]);
        } elseif ($lhi == 'bia') {
            return view('adminDesa.aset.kib.laporan_bia',  [
                'Akunasetkibas' => $Akunasetbia,
                'totals' => $total,
                'desas' => $desa,
                'kades' => $kades,
                'tahuns' => $tahun
            ]);
        }
    }
}
