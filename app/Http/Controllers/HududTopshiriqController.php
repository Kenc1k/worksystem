<?php

namespace App\Http\Controllers;

use App\Models\Hudud;
use App\Models\HududTopshiriq;
use App\Models\Topshiriq;
use Illuminate\Http\Request;

class HududTopshiriqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topshiriqs = Topshiriq::all();
        $hududs = Hudud::all();
        $hududTopshiriq = HududTopshiriq::all();
        return view('HududTopshirirq.index' , compact('topshiriqs' , 'hududs' , 'hududTopshiriq'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HududTopshiriq $hududTopshiriq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HududTopshiriq $hududTopshiriq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HududTopshiriq $hududTopshiriq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HududTopshiriq $hududTopshiriq)
    {
        //
    }
}
