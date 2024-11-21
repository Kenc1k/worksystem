<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Hudud;
use App\Models\HududTopshiriq;
use App\Models\Topshiriq;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TopshiriqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter inputs
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Query tasks with optional date filters
        $tasks = Topshiriq::query();

        if ($fromDate) {
            $tasks->where('muddat', '>=', $fromDate);
        }

        if ($toDate) {
            $tasks->where('muddat', '<=', $toDate);
        }

        // Execute the query and load related categories
        $tasks = $tasks->with('category')->get();

        return view('topshiriq.index', compact('tasks'));
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
    public function userTasks()
    {
        // Get the currently logged-in user's ID
        $userId = FacadesAuth::id();
    
        // Fetch tasks for the logged-in user
        $tasks = HududTopshiriq::select(
                'hudud_topshiriqs.id as task_id',
                'hududs.name as hudud_name',
                'hudud_topshiriqs.status',
                'topshiriqs.muddat' // Fetch muddat from topshiriqs
            )
            ->join('hududs', 'hudud_topshiriqs.hudud_id', '=', 'hududs.id')
            ->join('topshiriqs', 'hudud_topshiriqs.topshiriq_id', '=', 'topshiriqs.id') // Join with topshiriqs
            ->where('hududs.user_id', $userId)
            ->get();
    
        // Pass the tasks to the view
        return view('user_task.topshiriq', compact('tasks'));
    }
    public function myTasks()
    {
        $tasks = auth()->user()->tasks; // Retrieve tasks for the logged-in user

        return view('user_task.topshiriq', compact('tasks'));
    }
    
    
}
