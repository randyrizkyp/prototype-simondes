<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class BeritaController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $beritas = Berita::latest()->paginate(5);
        $infos = Admin::where('id', session('loggedEditor'))->first();
        return view('editor.berita.index',  [
            'infos' => $infos,
            'beritas' => $beritas
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
        return view('editor.berita.create',  [
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
            'image'     => 'required|image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('beritas', $image->hashName());

        $berita = Berita::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        if ($berita) {
            //redirect dengan pesan sukses
            return redirect()->route('berita.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('berita.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * edit
     *
     * @param  mixed $blog
     * @return void
     */
    public function edit(Berita $berita)
    {        
        $infos = Admin::where('id', session('loggedEditor'))->first();
        

        return view('editor.berita.edit',  [            
            'infos' => $infos,
            'beritas' => $berita
        ]);        
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request)
    {

        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required'
        ]);

        //get data Blog by ID
        $berita = Berita::findOrFail($request->id);

        if ($request->file('image') == "") {

            $berita->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        } else {

            //hapus old image
            Storage::disk('local')->delete('public/blogs/' . $berita->image);

            //upload new image
            $image = $request->file('image');
            $image->storeAs('beritas', $image->hashName());

            $berita->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }
        
        return redirect()->route('berita.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        if ($berita) {
            //redirect dengan pesan sukses
            return redirect()->route('berita.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('berita.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
