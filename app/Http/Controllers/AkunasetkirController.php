<?php

namespace App\Http\Controllers;

use App\Models\Akunasetkir;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class AkunasetkirController extends Controller
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
        $Akunasetkir = Akunasetkir::where([
            'asal_id' => $infos->asal_id,
            'tahun' => $tahun
        ])->get();

        return view('adminDesa.aset.kir.index',  [
            'infos' => $infos,
            'Akunasetkirs' => $Akunasetkir,
            'tahun' => $tahun
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        Akunasetkir::create([
            'asal_id'     => $request->asal_id,
            'tahun'       => $request->tahun,
            'ruangan'       => $request->ruangan,
            'file'        => $request->file,
        ]);
        $ruangan = str_replace(' ', '_', $request->ruangan);
        $ruangans = strtolower($ruangan);

        if ($request->file('file')) {
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "kir_" . $ruangans . $request->tahun . mt_rand(1, 20) . "." . $ext);

            Akunasetkir::where([
                'tahun' => $request->tahun,
                'asal_id' => $request->asal_id,
                'ruangan'    => $request->ruangan,
            ])->update([
                'file' => $file
            ]);
        }

        return back()->with('tambah', 'data berhasil ditambah');
    }

    public function update(Request $request)
    {
        $request->validate([
            'file'          => 'mimes:pdf|max:1024',
        ]);
        $ruangan = str_replace(' ', '_', $request->ruangan);
        $ruangans = strtolower($ruangan);
        if ($request->file('file')) {
            if ($request->file_lama && strpos($request->file_lama, $request->tahun)) {
                Storage::delete($request->file_lama);
            }
            $ext = $request->file->extension();
            $folder = "adminDesa/desa_" . $request->asal_id . "/" . $request->tahun . "/file_aset";
            $file = $request->file('file')->storeAs($folder, "kir_" . $ruangans . $request->tahun . mt_rand(1, 10) . "." . $ext);

            $Akunasetkir = Akunasetkir::findOrFail($request->id);
            $Akunasetkir->update([
                'file' => $file,
                'ruangan' => $request->ruangan
            ]);
        } else {
            $Akunasetkir = Akunasetkir::findOrFail($request->id);
            $Akunasetkir->update([
                'ruangan' => $request->ruangan
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
    public function destroy(Akunasetkir $Akunasetkir)
    {
        $data = Akunasetkir::findOrFail($Akunasetkir->id);
        $data->delete();
        if (strpos($Akunasetkir->file, $Akunasetkir->tahun)) {
            Storage::delete($data->file);
        }
        return back()->with('hapus', 'data berhasil dihapus');
    }

    public function copyAkunaset(Request $request)
    {

        $datatuju = Akunasetkir::where(
            [
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahuncopy,
            ]
        )->count();

        if ($request->timpadata) {
            Akunasetkir::where(
                [
                    'asal_id' => $request->asal_id,
                    'tahun' => $request->tahuncopy
                ]
            )->delete();

            $datas = Akunasetkir::where([
                'asal_id' => $request->asal_id,
                'tahun' => $request->tahunasal
            ])->get();
            foreach ($datas as $data) {
                $data['tahun'] = $request->tahuncopy;
                $data['id'] = '';
                $copydata = Akunasetkir::create($data->toArray());
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

        $datas = Akunasetkir::where([
            'asal_id' => $request->asal_id,
            'tahun' => $request->tahunasal
        ])->get();
        foreach ($datas as $data) {
            $data['tahun'] = $request->tahuncopy;
            $data['id'] = '';
            $copydata = Akunasetkir::create($data->toArray());
        }

        if ($copydata) {
            return redirect()->back()->with('success', 'Data berhasil di copy');
        }
    }
}
