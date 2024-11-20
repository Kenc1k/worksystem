<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Hudud;
use App\Models\Topshiriq;
use Illuminate\Http\Request;

class TopshiriqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Topshiriq::all();
        return view('topshiriq.index' , compact('tasks'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hududs = Hudud::all();
        $categories = Category::all();
        return view('topshiriq.create' , compact('hududs', 'categories'));
    }

    public function store(Request $request)
    {
        $filePath = null;
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('topshiriq', 'public');
        }
    
        $data = $request->validate([
            'category_id' => 'required',
            'ijrochi' => 'required',
            'title' => 'required',
            'file' => 'required|file',
            'muddat' => 'required',
            'hudud_id' => 'required|array', // Ensure hudud_id is an array
        ]);
    
        // Create the task
        $task = Topshiriq::create(array_merge($data, ['file' => $filePath]));
    
        // Attach each hudud_id as a separate entry in the pivot table
        foreach ($request->hudud_id as $hudud) {
            $task->hududs()->attach($hudud);
        }
    
        return redirect('/topshiriq');
    }
    


    /**
     * Display the specified resource.
     */
    public function show(Topshiriq $topshiriq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topshiriq $topshiriq)
    {
        $hududs = Hudud::all();
        $categories = Category::all();
        return view('topshiriq.edit' , compact('hududs' , 'categories' , 'topshiriq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topshiriq $topshiriq)
    {
        $filePath = $topshiriq->file; 

        if ($request->hasFile('file')) {
            if ($topshiriq->file && file_exists(storage_path('app/public/' . $topshiriq->file))) {
                unlink(storage_path('app/public/' . $topshiriq->file));
            }
            $file = $request->file('file');
            $filePath = $file->store('topshiriq', 'public');
        }

        $data = $request->validate([
            'category_id' => 'required', 
            'ijrochi' => 'required',           
            'title' => 'required',            
            'file' => 'nullable|file',          
            'muddat' => 'required',            
        ]);

        $topshiriq->update(array_merge($data, ['file' => $filePath]));

        $topshiriq->hududs()->detach(); 
        if ($request->has('hudud_id')) {
            $topshiriq->hududs()->attach($request->hudud_id); 
        }

        return redirect('/topshiriq');
    }

    /** 
     * Remove the specified resource from storage.
     */
    public function destroy(Topshiriq $topshiriq)
    {
        if ($topshiriq->file && file_exists(storage_path('app/public/' . $topshiriq->file))) {
            unlink(storage_path('app/public/' . $topshiriq->file)); // Delete the file from storage
        }

        $topshiriq->hududs()->detach();

        $topshiriq->delete();

        return redirect('/topshiriq');
    }
}
