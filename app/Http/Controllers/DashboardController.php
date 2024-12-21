<?php

namespace App\Http\Controllers;
use App\Models\Mobil;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        // Mengambil data mobil dari database
        $mobils = Mobil::all();

        // Mengirim data ke view
        return view('dashboard', compact('mobils'));
    }
}
 