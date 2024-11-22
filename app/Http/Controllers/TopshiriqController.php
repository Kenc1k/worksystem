<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Hudud;
use App\Models\HududTopshiriq;
use App\Models\Topshiriq;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage;

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
 
         // Base query for filtered tasks
         $tasks = Topshiriq::query()
             ->with(['category', 'hududTopshiriqs.hudud']);
 
         // Apply date filters if provided
         if ($fromDate) {
             $tasks->where('muddat', '>=', $fromDate);
         }
         if ($toDate) {
             $tasks->where('muddat', '<=', $toDate);
         }
 
         // Calculate statistics
         $now = Carbon::now();
         $statistics = [
             // Total tasks
             'total' => Topshiriq::count(),
             
             // Tasks due in 2 days
             'twoDaysLater' => Topshiriq::whereDate('muddat', $now->copy()->addDays(2))
                 ->whereHas('hududTopshiriqs', function($query) {
                     $query->where('status', HududTopshiriq::STATUS_SEND);
                 })->count(),
             
             // Tasks due tomorrow
             'tomorrow' => Topshiriq::whereDate('muddat', $now->copy()->addDay())
                 ->whereHas('hududTopshiriqs', function($query) {
                     $query->where('status', HududTopshiriq::STATUS_SEND);
                 })->count(),
             
             // Rejected and expired tasks
             'rejected' => HududTopshiriq::where('status', HududTopshiriq::STATUS_REJECTED)
                 ->orWhere(function($query) use ($now) {
                     $query->whereHas('topshiriq', function($q) use ($now) {
                         $q->whereDate('muddat', '<', $now);
                     })->where('status', HududTopshiriq::STATUS_SEND);
                 })->count()
         ];
 
         $tasks = $tasks->get();
 
         // Mark expired tasks
         $tasks->each(function($task) use ($now) {
             if ($task->muddat < $now && $task->hududTopshiriqs->contains('status', HududTopshiriq::STATUS_SEND)) {
                 $task->hududTopshiriqs->each(function($hududTopshiriq) {
                     if ($hududTopshiriq->status === HududTopshiriq::STATUS_SEND) {
                         $hududTopshiriq->update(['status' => HududTopshiriq::STATUS_EXPIRED]);
                     }
                 });
             }
         });
 
         return view('topshiriq.index', compact('tasks', 'statistics'));
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
        $request->validate([
            'title' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'ijrochi' => 'required',
            'file' => 'nullable|file|mimes:pdf,docx,jpeg,png|max:2048',
            'muddat' => 'required|date',
            'hududs' => 'required|array',
            'hududs.*' => 'exists:hududs,id',
        ]);
    
        $filePath = null;
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('topshiriq' , 'public');
        }
    
        $topshiriq = Topshiriq::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'ijrochi' => $request->ijrochi,
            'file' => $filePath,
            'muddat' => $request->muddat,
        ]);
    
        foreach ($request->hududs as $hududId) {
            HududTopshiriq::create([
                'hudud_id' => $hududId,
                'topshiriq_id' => $topshiriq->id,
                'status' => 'send',
            ]);
        }
    
        return redirect()->route('topshiriq.index')->with('success', 'Task created successfully.');
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
    public function update(Request $request, $id)
    {
        $task = Topshiriq::findOrFail($id);
    
        $request->validate([
            'title' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'ijrochi' => 'required',
            'file' => 'nullable|file|mimes:pdf,docx,jpeg,png|max:2048',
            'muddat' => 'required|date',
        ]);
    
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($task->file && file_exists(public_path($task->file))) {
                unlink(public_path($task->file));
            }
    
            $file = $request->file('file');
            $task->file = 'topshiriq/' . $file->getClientOriginalName();
            $file->move(public_path('topshiriq'), $file->getClientOriginalName()); // Save to /public/topshiriq
        }
    
        $task->update($request->only('title', 'category_id', 'ijrochi', 'muddat'));
    
        return redirect()->route('topshiriq.index')->with('success', 'Task updated successfully.');
    }
    
    
    

    /** 
     * Remove the specified resource from storage.
     */
    public function destroy(Topshiriq $topshiriq)
    {
        if ($topshiriq->file && file_exists(public_path($topshiriq->file))) {
            unlink(public_path($topshiriq->file)); // Delete the file from storage
        }
    
        $topshiriq->hududs()->detach();
    
        $topshiriq->delete();
    
        return redirect()->route('topshiriq.index')->with('success', 'Task deleted successfully.');
    }
    
    public function userTasks()
    {
        $userId = FacadesAuth::id();
    
        $tasks = HududTopshiriq::select(
            'hudud_topshiriqs.id as task_id',
            'hududs.name as hudud_name',
            'hudud_topshiriqs.status',
            'topshiriqs.muddat'
            )
            ->join('hududs', 'hudud_topshiriqs.hudud_id', '=', 'hududs.id')
            ->join('topshiriqs', 'hudud_topshiriqs.topshiriq_id', '=', 'topshiriqs.id')
            ->where('hududs.user_id', $userId)
            ->get();
            
            // dd($tasks);
        // Pass the tasks to the view
        return view('user_task.topshiriq', compact('tasks'));
    }

    public function myTasks()
    {
        $user = FacadesAuth::user(); // Get the authenticated user
    
        // Get the tasks associated with the user's hududs
        $tasks = HududTopshiriq::select(
            'hudud_topshiriqs.id as task_id',
            'hududs.name as hudud_name',
            'hudud_topshiriqs.status',
            'topshiriqs.muddat',
            'topshiriqs.title'  // Add any other fields you need
        )
        ->join('hududs', 'hudud_topshiriqs.hudud_id', '=', 'hududs.id')
        ->join('topshiriqs', 'hudud_topshiriqs.topshiriq_id', '=', 'topshiriqs.id')
        ->where('hududs.user_id', $user->id) // Ensure user_id is checked correctly
        ->get();
    
        return view('user_task.topshiriq', compact('tasks'));
    }
}
