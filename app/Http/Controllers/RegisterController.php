<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Asal;

class RegisterController extends Controller
{
    public function index()
    {
        return view('superadmin.register', [
            'datas' => Admin::all()
        ]);
    }
    public function formTambah()
    {
        return view('superadmin.formTambah', [
            'asals' => Asal::all()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:30',
            'username' => 'required|max:20|unique:admins',
            'password' => 'required|min:5|max:10',
            'asal_id' => 'required',
            'obrik' => 'required',
            'role' => 'required'
        ]);

        Admin::create($validatedData);
        return redirect('/register')->with('success', 'berhasil tambah admin');
    }

    public function delete(Admin $data)
    {
        Admin::destroy($data->id);
        return redirect('/register')->with('success', 'berhasil hapus data');
    }
}
