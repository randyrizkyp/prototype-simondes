<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class GaleriController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $galeris = Galeri::latest()->paginate(5);
        $infos = Admin::where('id', session('loggedEditor'))->first();
        return view('editor.galeri.index',  [
            'infos' => $infos,
            'galeris' => $galeris
        ]);
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {               
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
            'image'       => 'required|image|mimes:png,jpg,jpeg|max:1024',
            'description' => 'required'            
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('galeris', $image->hashName());

        $galeri = Galeri::create([
            'image'     => $image->hashName(),
            'description' => $request->description            
        ]);

        if ($galeri) {
            //redirect dengan pesan sukses
            return redirect()->route('galeri.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('galeri.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * edit
     *
     * @param  mixed $blog
     * @return void
     */
    public function edit(Request $request)
    {                
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
            'description'   => 'required'
        ]);

        //get data Blog by ID
        $galeri = Galeri::findOrFail($request->id);

        if ($request->file('image') == "") {

            $galeri->update([
                'description'     => $request->description                
            ]);
        } else {

            //hapus old image
            Storage::disk('local')->delete('public/galeris/' . $galeri->image);

            //upload new image
            $image = $request->file('image');
            $image->storeAs('galeris', $image->hashName());

            $galeri->update([
                'image'     => $image->hashName(),
                'description'     => $request->description                
            ]);
        }
        
        return redirect()->route('galeri.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);
        $galeri->delete();

        if ($galeri) {
            //redirect dengan pesan sukses
            return redirect()->route('galeri.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('galeri.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
