<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class PeraturanController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $peraturan = Peraturan::orderBy('no_urut')->get();
        $infos = Admin::where('id', session('loggedEditor'))->first();
        return view('editor.peraturan.index',  [
            'infos' => $infos,
            'peraturans' => $peraturan,
        ]);
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {        
        $infos = Admin::where('id', session('loggedEditor'))->first();
        return view('editor.peraturan.create',  [
            'infos' => $infos
        ]);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'dokumen'   => 'required|mimes:pdf,jpg,jpeg',
            'title'     => 'required',
            'nomor'     => 'max:3',
            'tahun'     => 'max:4',
            'no_urut'   => 'required',
        ]);

        //upload image
        $dokumen = $request->file('dokumen');
        $dokumen->storeAs('peraturans', $dokumen->hashName());

        $peraturan = Peraturan::create([
            'dokumen'    => $dokumen->hashName(),
            'title'     => $request->title,
            'nomor'     => $request->nomor,
            'tahun'     => $request->tahun,
            'no_urut'   => $request->no_urut,
        ]);

        if ($peraturan) {
            //redirect dengan pesan sukses
            return redirect()->route('peraturan.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('peraturan.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * edit
     *
     * @param  mixed $blog
     * @return void
     */
    public function edit(Peraturan $peraturan)
    {    
        $infos = Admin::where('id', session('loggedEditor'))->first();
        return view('editor.peraturan.edit',  [
            'peraturan' => $peraturan,
            'infos' => $infos
        ]);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Peraturan $peraturan)
    {
        $this->validate($request, [
            'title'     => 'required',
            'nomor'     => 'max:3',
            'tahun'     => 'max:4',
            'no_urut'   => 'required',
        ]);

        //get data Blog by ID
        $peraturan = Peraturan::findOrFail($peraturan->id);

        if ($request->file('dokumen') == "") {

            $peraturan->update([
                'title'     => $request->title,
                'nomor'     => $request->nomor,
                'tahun'     => $request->tahun,
                'no_urut'   => $request->no_urut,
            ]);
        } else {

            //hapus old image
            Storage::disk('local')->delete('public/peraturans/' . $peraturan->dokumen);

            //upload new image
            $dokumen = $request->file('dokumen');
            $dokumen->storeAs('peraturans', $dokumen->hashName());

            $peraturan->update([
                'dokumen'     => $dokumen->hashName(),
                'title'     => $request->title,
                'nomor'     => $request->nomor,
                'tahun'     => $request->tahun,
                'no_urut'   => $request->no_urut,
            ]);
        }

        if ($peraturan) {
            //redirect dengan pesan sukses
            return redirect()->route('peraturan.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('peraturan.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $peraturan = Peraturan::findOrFail($id);
        $peraturan->delete();

        if ($peraturan) {
            //redirect dengan pesan sukses
            return redirect()->route('peraturan.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('peraturan.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
