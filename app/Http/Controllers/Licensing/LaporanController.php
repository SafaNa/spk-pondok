<?php

namespace App\Http\Controllers\Licensing;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return view('licensing.laporan');
    }
}
