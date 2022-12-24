<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $tahun = now()->format('Y');

        $infos = Admin::with('asal')->where('id', session('loggedAdminDesa'))->first();
        return view('adminDesa.Progress.index', [
            'tahun' => $tahun,
            'infos' => $infos
        ]);
    }
}
