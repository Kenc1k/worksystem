<?php

namespace App\Http\Controllers;

use App\Models\Hudud;
use App\Models\User;
use Illuminate\Http\Request;

class HududController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hududs = Hudud::with('user')->paginate(10); // Eager load the 'user' relationship
        return view('hudud.index', compact('hududs'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('hudud.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $data = $request->validate([
            'user_id'=>'required',
            'name'=>'required',
        ]);

        Hudud::create($data);

        return redirect()->route('hudud.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hudud $hudud)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = User::all();
        $hudud = Hudud::findOrFail($id);
        return view('hudud.edit' , compact('hudud' , 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id'=>'required',
            'name'=>'required',
        ]);

        $hudud = Hudud::findOrFail($id);
        $hudud->update($data);

        return redirect('/hudud');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hudud = Hudud::findOrfail($id);
        $hudud->delete();

        return redirect('/hudud');
    }
}
