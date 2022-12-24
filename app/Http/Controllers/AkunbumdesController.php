<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Akunbumdes;
use App\Models\Keuanganbumdes;
use Illuminate\Http\Request;
use App\Models\Admin;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Storage;

class AkunbumdesController extends Controller
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
        $akunbumdes = Akunbumdes::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        $akunbumdesterdaftar = Akunbumdes::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun,
            'terdaftar' => 'Terdaftar Kemendes'
        ])->get();

        return view('adminDesa.bumdes.index',  [
            'infos' => $infos,
            'Akunbumdess' => $akunbumdes,
            'Akunbumdesterdaftars' => $akunbumdesterdaftar,
            'tahun' => $tahun
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'perdes_pembentukan' => 'mimes:pdf|max:1024',
            'buku_rekening' => 'mimes:pdf|max:1024',
            'sk_kepengurusan' => 'mimes:pdf|max:1024',
            'adart_bumdes' => 'mimes:pdf|max:1024',
        ]);
        $db = Akunbumdes::create([
            'asal_id'     => $request->asal_id,
            'tahun'       => $request->tahun,
            'nama_bumdes'   => $request->nama_bumdes,
            'terdaftar'   => $request->terdaftar,
            'sifat'   => $request->sifat,
        ]);

        if ($request->file('perdes_pembentukan')) {
            $ext = $request->perdes_pembentukan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('perdes_pembentukan')->storeAs($folder, "perdes_pembentukan_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunbumdes::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
            ])->update([
                'perdes_pembentukan' => $file
            ]);
        }

        if ($request->file('buku_rekening')) {
            $ext = $request->buku_rekening->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('buku_rekening')->storeAs($folder, "buku_rekening_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunbumdes::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
            ])->update([
                'buku_rekening' => $file
            ]);
        }

        if ($request->file('sk_kepengurusan')) {
            $ext = $request->sk_kepengurusan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('sk_kepengurusan')->storeAs($folder, "sk_kepengurusan_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunbumdes::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
            ])->update([
                'sk_kepengurusan' => $file
            ]);
        }

        if ($request->file('adart_bumdes')) {
            $ext = $request->adart_bumdes->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('adart_bumdes')->storeAs($folder, "adart_bumdes_" . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunbumdes::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
            ])->update([
                'adart_bumdes' => $file
            ]);
        }

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function update(Request $request)
    {
        $request->validate([
            'bac' => 'mimes:pdf|max:1024',
            'perdes' => 'mimes:pdf|max:1024',
            'adart' => 'mimes:pdf|max:1024',
            'rencana_program' => 'mimes:pdf|max:1024',
            'struktur_organisasi' => 'mimes:pdf|max:1024',
            'lap_pengawas' => 'mimes:pdf|max:1024',
            'lap_keuangan' => 'mimes:pdf|max:1024',
            'lap_sem1' => 'mimes:pdf|max:1024',
            'lap_sem2' => 'mimes:pdf|max:1024',
            'lap_tahunan' => 'mimes:pdf|max:1024',
            'sk_kepengurusan' => 'mimes:pdf|max:1024',
            'buku_rekening' => 'mimes:pdf|max:1024',
        ]);
        $db = Akunbumdes::findOrFail($request->id);

        if ($request->nama_bumdes) {
            $db->update([
                'nama_bumdes' => $request->nama_bumdes,
            ]);
        }
        if ($request->terdaftar) {
            $db->update([
                'terdaftar' => $request->terdaftar,
            ]);
        }
        if ($request->sifat) {
            $db->update([
                'sifat' => $request->sifat,
            ]);
        }
        if ($request->modal_pertama) {
            $db->update([
                'modal_pertama' => $request->modal_pertama,
            ]);
        }
        if ($request->dana_modal_pertama) {
            $dana = preg_replace('/\D/', '', $request->dana_modal_pertama);
            $db->update([
                'dana_modal_pertama' => $dana,
            ]);
        }
        if ($request->modal_terakhir) {
            $db->update([
                'modal_terakhir' => $request->modal_terakhir,
            ]);
        }
        if ($request->dana_modal_terakhir) {
            $dana = preg_replace('/\D/', '', $request->dana_modal_terakhir);
            $db->update([
                'dana_modal_terakhir' => $dana,
            ]);
        }

        if ($request->file('bac')) {
            if ($request->old_bac && strpos($request->old_bac, $request->tahun)) {
                Storage::delete($request->old_bac);
            }
            $ext = $request->bac->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('bac')->storeAs($folder, "bac_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'bac' => $file,
            ]);
        }
        if ($request->file('perdes')) {
            if ($request->old_perdes && strpos($request->old_perdes, $request->tahun)) {
                Storage::delete($request->old_perdes);
            }
            $ext = $request->perdes->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('perdes')->storeAs($folder, "perdes_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'perdes' => $file,
            ]);
        }
        if ($request->file('adart')) {
            if ($request->old_adart && strpos($request->old_adart, $request->tahun)) {
                Storage::delete($request->old_adart);
            }
            $ext = $request->adart->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('adart')->storeAs($folder, "adart_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'adart' => $file,
            ]);
        }
        if ($request->file('rencana_program')) {
            if ($request->old_rencana_program && strpos($request->old_rencana_program, $request->tahun)) {
                Storage::delete($request->old_rencana_program);
            }
            $ext = $request->rencana_program->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('rencana_program')->storeAs($folder, "rencana_program_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'rencana_program' => $file,
            ]);
        }
        if ($request->file('struktur_organisasi')) {
            if ($request->old_struktur_organisasi && strpos($request->old_struktur_organisasi, $request->tahun)) {
                Storage::delete($request->old_struktur_organisasi);
            }
            $ext = $request->struktur_organisasi->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('struktur_organisasi')->storeAs($folder, "struktur_organisasi_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'struktur_organisasi' => $file,
            ]);
        }
        if ($request->file('lap_pengawas')) {
            if ($request->old_lap_pengawas && strpos($request->old_lap_pengawas, $request->tahun)) {
                Storage::delete($request->old_lap_pengawas);
            }
            $ext = $request->lap_pengawas->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('lap_pengawas')->storeAs($folder, "lap_pengawas_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'lap_pengawas' => $file,
            ]);
        }
        if ($request->file('lap_keuangan')) {
            if ($request->old_lap_keuangan && strpos($request->old_lap_keuangan, $request->tahun)) {
                Storage::delete($request->old_lap_keuangan);
            }
            $ext = $request->lap_keuangan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('lap_keuangan')->storeAs($folder, "lap_keuangan_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'lap_keuangan' => $file,
            ]);
        }
        if ($request->file('lap_sem1')) {
            if ($request->old_lap_sem1 && strpos($request->old_lap_sem1, $request->tahun)) {
                Storage::delete($request->old_lap_sem1);
            }
            $ext = $request->lap_sem1->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('lap_sem1')->storeAs($folder, "lap_sem1_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'lap_sem1' => $file,
            ]);
        }
        if ($request->file('lap_sem2')) {
            if ($request->old_lap_sem2 && strpos($request->old_lap_sem2, $request->tahun)) {
                Storage::delete($request->old_lap_sem2);
            }
            $ext = $request->lap_sem2->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('lap_sem2')->storeAs($folder, "lap_sem2_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'lap_sem2' => $file,
            ]);
        }
        if ($request->file('lap_tahunan')) {
            if ($request->old_lap_tahunan && strpos($request->old_lap_tahunan, $request->tahun)) {
                Storage::delete($request->old_lap_tahunan);
            }
            $ext = $request->lap_tahunan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('lap_tahunan')->storeAs($folder, "lap_tahunan_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'lap_tahunan' => $file,
            ]);
        }
        if ($request->file('sk_kepengurusan')) {
            if ($request->old_sk_kepengurusan && strpos($request->old_sk_kepengurusan, $request->tahun)) {
                Storage::delete($request->old_sk_kepengurusan);
            }
            $ext = $request->sk_kepengurusan->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('sk_kepengurusan')->storeAs($folder, "sk_kepengurusan_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'sk_kepengurusan' => $file,
            ]);
        }
        if ($request->file('buku_rekening')) {
            if ($request->old_buku_rekening && strpos($request->old_buku_rekening, $request->tahun)) {
                Storage::delete($request->old_buku_rekening);
            }
            $ext = $request->buku_rekening->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
            $file = $request->file('buku_rekening')->storeAs($folder, "buku_rekening_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $db->update([
                'buku_rekening' => $file,
            ]);
        }

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
        $akunbumdes = Akunbumdes::findOrFail($id);
        $akunbumdes->delete();

        return back()->with('hapus', 'data berhasil dihapus');
    }

    public function copyAkunbumdes(Request $request)
    {

        $datatuju = Akunbumdes::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunbumdes::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunbumdes::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunbumdes::create($data->toArray());
            }

            if ($copydata) {
                return redirect()->back()->with('success', 'Data berhasil di copy');
            }

            exit();
            die();
        }

        if ($datatuju > 0) {
            return back()->with('timpaAllb', $request->tahuncopy);
            exit();
            die();
        }

        $datas = Akunbumdes::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunbumdes::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }

    // public function akuntabilitas(Request $request)
    // {
    //     $data = [
    //         'asal_id' => $request->asal_id,
    //         'tahun' => $request->tahun,
    //         'modal_ini' => $request->penyertaan,
    //     ];
    //     Akunbumdes::create($data);
    //     return back();
    // }

    // public function akuntabilitas_update(Request $request)
    // {
    //     $db = akunbumdes::findOrFail($request->id);

    //     if ($request->modal_ini) {
    //         $db->update([
    //             'modal_ini' => $request->modal_ini,
    //         ]);
    //     }

    //     $db->update([
    //         'rencana_kontribusi' => $request->rencana_kontribusi,
    //         'realisasi_kontribusi' => $request->realisasi_kontribusi,
    //         'tahun_modal_pertama' => $request->tahun_modal_pertama,
    //         'tahun_modal_terakhir' => $request->tahun_modal_terakhir,
    //     ]);

    //     $request->validate([
    //         'lapkeu_lalu'     => 'mimes:pdf|max:10240',
    //         'aset_lalu'     => 'mimes:pdf|max:10240',
    //         'proposal_ini'     => 'mimes:pdf|max:10240',
    //         'sk_ini'     => 'mimes:pdf|max:10240',
    //     ]);

    //     if ($request->file('lapkeu_lalu')) {
    //         if ($request->old_lapkeu_lalu && strpos($request->old_lapkeu_lalu, $request->tahun)) {
    //             Storage::delete($request->old_lapkeu_lalu);
    //         }
    //         $ext = $request->lapkeu_lalu->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('lapkeu_lalu')->storeAs($folder, "lapkeu_lalu_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'lapkeu_lalu' => $file
    //         ]);
    //     }

    //     if ($request->file('aset_lalu')) {
    //         if ($request->old_aset_lalu && strpos($request->old_aset_lalu, $request->tahun)) {
    //             Storage::delete($request->old_aset_lalu);
    //         }
    //         $ext = $request->aset_lalu->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('aset_lalu')->storeAs($folder, "aset_lalu_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'aset_lalu' => $file
    //         ]);
    //     }

    //     if ($request->file('proposal_ini')) {
    //         if ($request->old_proposal_ini && strpos($request->old_proposal_ini, $request->tahun)) {
    //             Storage::delete($request->old_2);
    //         }
    //         $ext = $request->proposal_ini->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('proposal_ini')->storeAs($folder, "proposal_ini_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'proposal_ini' => $file
    //         ]);
    //     }

    //     if ($request->file('sk_kepengurusan_ini')) {
    //         if ($request->old_sk_kepengurusan_ini && strpos($request->old_sk_kepengurusan_ini, $request->tahun)) {
    //             Storage::delete($request->old_sk_kepengurusan_ini);
    //         }
    //         $ext = $request->sk_kepengurusan_ini->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('sk_kepengurusan_ini')->storeAs($folder, "sk_kepengurusan_ini_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'sk_kepengurusan_ini' => $file
    //         ]);
    //     }

    //     if ($request->file('bukti_setor')) {
    //         if ($request->old_bukti_setor && strpos($request->old_bukti_setor, $request->tahun)) {
    //             Storage::delete($request->old_bukti_setor);
    //         }
    //         $ext = $request->bukti_setor->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('bukti_setor')->storeAs($folder, "bukti_setor_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'bukti_setor' => $file
    //         ]);
    //     }

    //     //Update Akuntabilitas
    //     if ($request->file('proposal_pertama')) {
    //         if ($request->old_proposal_pertama && strpos($request->old_proposal_pertama, $request->tahun)) {
    //             Storage::delete($request->old_proposal_pertama);
    //         }
    //         $ext = $request->proposal_pertama->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('proposal_pertama')->storeAs($folder, "proposal_pertama_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'proposal_pertama' => $file
    //         ]);
    //     }

    //     if ($request->file('proposal_terakhir')) {
    //         if ($request->old_proposal_terakhir && strpos($request->old_proposal_terakhir, $request->tahun)) {
    //             Storage::delete($request->old_proposal_terakhir);
    //         }
    //         $ext = $request->proposal_terakhir->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('proposal_terakhir')->storeAs($folder, "proposal_terakhir_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'proposal_terakhir' => $file
    //         ]);
    //     }

    //     if ($request->file('sk_kepengurusan_terakhir')) {
    //         if ($request->old_sk_kepengurusan_terakhir && strpos($request->old_sk_kepengurusan_terakhir, $request->tahun)) {
    //             Storage::delete($request->old_sk_kepengurusan_terakhir);
    //         }
    //         $ext = $request->sk_kepengurusan_terakhir->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('sk_kepengurusan_terakhir')->storeAs($folder, "sk_kepengurusan_terakhir_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'sk_kepengurusan_terakhir' => $file
    //         ]);
    //     }

    //     if ($request->file('lapkeu_terakhir')) {
    //         if ($request->old_lapkeu_terakhir && strpos($request->old_lapkeu_terakhir, $request->tahun)) {
    //             Storage::delete($request->old_lapkeu_terakhir);
    //         }
    //         $ext = $request->lapkeu_terakhir->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('lapkeu_terakhir')->storeAs($folder, "lapkeu_terakhir_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'lapkeu_terakhir' => $file
    //         ]);
    //     }

    //     if ($request->file('aset_terakhir')) {
    //         if ($request->old_aset_terakhir && strpos($request->old_aset_terakhir, $request->tahun)) {
    //             Storage::delete($request->old_aset_terakhir);
    //         }
    //         $ext = $request->aset_terakhir->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('aset_terakhir')->storeAs($folder, "aset_terakhir_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'aset_terakhir' => $file
    //         ]);
    //     }

    //     if ($request->file('foto_rekening_terakhir')) {
    //         if ($request->old_foto_rekening_terakhir && strpos($request->old_foto_rekening_terakhir, $request->tahun)) {
    //             Storage::delete($request->old_foto_rekening_terakhir);
    //         }
    //         $ext = $request->foto_rekening_terakhir->extension();
    //         $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_bumdes";
    //         $file = $request->file('foto_rekening_terakhir')->storeAs($folder, "foto_rekening_terakhir_" . $request->tahun . mt_rand(1, 10) . "." . $ext);

    //         $db->update([
    //             'foto_rekening_terakhir' => $file
    //         ]);
    //     }

    //     return back();
    // }
}
