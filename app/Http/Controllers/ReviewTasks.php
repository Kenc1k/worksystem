<?php

namespace App\Http\Controllers;

use App\Models\Hudud;
use App\Models\HududTopshiriq;
use App\Models\Topshiriq;
use Illuminate\Http\Request;

class ReviewTasks extends Controller
{
    public function index()
    {
        $hududs = Hudud::all();
        $topshiriqs = Topshiriq::with('hududTopshiriq')->get(); // Eager load the relationship
        return view('review.index', compact('hududs', 'topshiriqs'));
    }
    
}
