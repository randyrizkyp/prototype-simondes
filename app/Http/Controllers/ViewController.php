<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class ViewController extends Controller
{
    public function lihat($id)
    {
        $article = DB::table('beritas')->where('id', $id)->first();
        return view('beranda.view', ['article' => $article]);
    }

    public function lihatperaturan($id)
    {
        $article = DB::table('peraturans')->where('id', $id)->first();
        return view('beranda.viewperaturan', ['article' => $article]);
    }

    public function lihatpengumuman($id)
    {
        $article = DB::table('pengumumans')->where('id', $id)->first();
        return view('beranda.viewpengumuman', ['article' => $article]);
    }
}
