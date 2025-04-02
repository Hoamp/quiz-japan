<?php

namespace App\Http\Controllers;

use App\Models\Ordering;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $ordering = Ordering::latest()->get();
        return view('dashboard', compact('ordering'));
    }
}
