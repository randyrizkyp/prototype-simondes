<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use App\Models\Post;
use App\Models\Blog;
use App\Models\Pengumuman;


class EditorController extends Controller
{

    public function index()
    {
        $infos = Admin::where('id', session('loggedEditor'))->first();

        return view('editor.index', [
            'infos' => $infos,

        ]);
    }

    public function logoutEditor(Request $request)
    {
        if (session()->has('loggedEditor')) {
            session()->pull('loggedEditor');
            session()->pull('loggedAdmin');
            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/');
        }
    }


    public function berita()
    {
        $infos = Admin::where('id', session('loggedEditor'))->first();
        $berita = Post::select('*')->get();
        return view('editor.berita.index', [
            'infos' => $infos,
            'beritas' => $berita

        ]);
    }

    // public function blog()
    // {
    //     $infos = Admin::where('id', session('loggedEditor'))->first();
    //     $blog = Blog::select('*')->get();
    //     return view('editor.blog.index', [
    //         'infos' => $infos,
    //         'blog' => $blog
    //     ]);
    // }

    public function create()
    {
        return view('blog.create',);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image'     => 'required|image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/blogs', $image->hashName());

        $blog = Blog::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        if ($blog) {
            //redirect dengan pesan sukses
            return redirect()->route('blog.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('blog.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * edit
     *
     * @param  mixed $blog
     * @return void
     */
    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required'
        ]);

        //get data Blog by ID
        $blog = Blog::findOrFail($blog->id);

        if ($request->file('image') == "") {

            $blog->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        } else {

            //hapus old image
            Storage::disk('local')->delete('public/blogs/' . $blog->image);

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());

            $blog->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        if ($blog) {
            //redirect dengan pesan sukses
            return redirect()->route('blog.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('blog.index')->with(['error' => 'Data Gagal Diupdate!']);
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
        $blog = Blog::findOrFail($id);
        $blog->delete();

        if ($blog) {
            //redirect dengan pesan sukses
            return redirect()->route('blog.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('blog.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
