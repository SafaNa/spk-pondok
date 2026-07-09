<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('licensing.notifikasi');
    }
}
