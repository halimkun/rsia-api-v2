<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiPasienController extends Controller
{
    public function index(Request $request)
    {
        return view('notifikasi.pasien.index', [
            'title' => 'Notifikasi Pasien',
            'content' => 'Ini adalah halaman notifikasi pasien'
        ]);
    }
}
