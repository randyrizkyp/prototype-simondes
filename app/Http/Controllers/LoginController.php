<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class LoginController extends Controller
{
    public function cekMasuk(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $adminInfo = Admin::where('username', '=', $validatedData['username'])->first();
        if (!$adminInfo) {
            return back()->with('fail', 'username tidak ada!');
        } else {
            // cek password
            if ($adminInfo->password == $validatedData['password']) {
                $request->session()->put('loggedAdmin', $adminInfo->role);
                //cek role
                if ($adminInfo->role == 'admin_desa') {
                    $request->session()->put('loggedAdminDesa', $adminInfo->id);
                    return redirect('/adminDesa');
                } elseif ($adminInfo->role == 'admin_irbanwil') {
                    $request->session()->put('loggedAdminIrwil', $adminInfo->id);
                    return redirect('/irwil');
                } elseif ($adminInfo->role == 'admin_super') {
                    $request->session()->put('loggedAdminSuper', $adminInfo->id);
                    return redirect('/superadmin');
                } elseif ($adminInfo->role == 'editor') {
                    $request->session()->put('loggedEditor', $adminInfo->id);
                    return redirect('/editor');
                }
            } else {
                return back()->with('fail', 'password salah!');
            }
        }
    }
}
