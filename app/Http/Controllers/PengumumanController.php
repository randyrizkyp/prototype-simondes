<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class PengumumanController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $pengumumans = Pengumuman::latest()->paginate(5);
        $infos = Admin::where('id', session('loggedEditor'))->first();
        return view('editor.pengumuman.index',  [
            'infos' => $infos,
            'pengumumans' => $pengumumans
        ]);
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('editor.pengumuman.create');
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
            'title'     => 'required',
            'nomor'     => 'max:3',
            'tahun'     => 'max:4',
        ]);


        $pengumuman = Pengumuman::create([
            'title'     => $request->title,
            'nomor'     => $request->nomor,
            'tahun'     => $request->tahun,
        ]);

        if ($pengumuman) {
            //redirect dengan pesan sukses
            return redirect()->route('pengumuman.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('pengumuman.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * edit
     *
     * @param  mixed $blog
     * @return void
     */
    public function edit(Pengumuman $pengumuman)
    {
        return view('editor.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $this->validate($request, [
            'title'     => 'required',
            'nomor'     => 'max:3',
            'tahun'     => 'max:4',
        ]);

        //get data Blog by ID
        $pengumuman = Pengumuman::findOrFail($pengumuman->id);

        $pengumuman->update([
            'title'     => $request->title,
            'nomor'     => $request->nomor,
            'tahun'     => $request->tahun,
        ]);


        if ($pengumuman) {
            //redirect dengan pesan sukses
            return redirect()->route('pengumuman.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('pengumuman.index')->with(['error' => 'Data Gagal Diupdate!']);
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
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        if ($pengumuman) {
            //redirect dengan pesan sukses
            return redirect()->route('pengumuman.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('pengumuman.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
