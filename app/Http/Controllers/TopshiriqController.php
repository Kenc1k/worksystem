<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Hudud;
use App\Models\HududTopshiriq;
use App\Models\Topshiriq;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TopshiriqController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         $fromDate = $request->input('from_date');
         $toDate = $request->input('to_date');
         $status = $request->input('status');
     
         // Query tasks with relationships
         $tasks = Topshiriq::query()->with(['category', 'hududTopshiriqs.hudud']);
     
         // Apply date filters
         if ($fromDate) {
             $tasks->where('muddat', '>=', $fromDate);
         }
         if ($toDate) {
             $tasks->where('muddat', '<=', $toDate);
         }
     
         // Apply status filter for 'rejected'
         if ($status === 'rejected') {
             $tasks->whereHas('hududTopshiriqs', function ($query) {
                 $query->where('status', HududTopshiriq::STATUS_REJECTED)
                       ->orWhere(function ($query) {
                           $query->whereHas('topshiriq', function ($q) {
                               $q->whereDate('muddat', '<', now());
                           })->where('status', HududTopshiriq::STATUS_SEND);
                       });
             });
         }
     
         // Get current timestamp
         $now = Carbon::now();
     
         // Task statistics
         $statistics = [
             'total' => Topshiriq::count(),
             'twoDaysLater' => Topshiriq::whereDate('muddat', $now->copy()->addDays(2))
                 ->whereHas('hududTopshiriqs', function ($query) {
                     $query->where('status', HududTopshiriq::STATUS_SEND);
                 })->count(),
             'tomorrow' => Topshiriq::whereDate('muddat', $now->copy()->addDay())
                 ->whereHas('hududTopshiriqs', function ($query) {
                     $query->where('status', HududTopshiriq::STATUS_SEND);
                 })->count(),
             'rejected' => HududTopshiriq::where('status', HududTopshiriq::STATUS_REJECTED)
                 ->orWhere(function ($query) use ($now) {
                     $query->whereHas('topshiriq', function ($q) use ($now) {
                         $q->whereDate('muddat', '<', $now);
                     })->where('status', HududTopshiriq::STATUS_SEND);
                 })->count(),
         ];
     
         // Fetch tasks
         $tasks = $tasks->get();
     
         // Update the status of expired tasks
         $tasks->each(function ($task) use ($now) {
             if ($task->muddat < $now && $task->hududTopshiriqs->contains('status', HududTopshiriq::STATUS_SEND)) {
                 $task->hududTopshiriqs->each(function ($hududTopshiriq) {
                     if ($hududTopshiriq->status === HududTopshiriq::STATUS_SEND) {
                         $hududTopshiriq->update(['status' => HududTopshiriq::STATUS_EXPIRED]);
                     }
                 });
             }
         });
         $messages = HududTopshiriq::with('user') // Eager load the user relationship
         ->where('status', 'done')
         ->get();
         $hududTopshiriq = HududTopshiriq::all();
         return view('topshiriq.index', compact('tasks', 'statistics', 'messages', 'hududTopshiriq'));
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
    
    
    public function status($id, $status)
    {
        // Find the task by ID
        $task = Topshiriq::findOrFail($id);

        // Check the status and update
        $task->hududTopshiriqs->each(function ($hududTopshiriq) use ($status) {
            $hududTopshiriq->update(['status' => $status]);
        });

        return redirect()->route('topshiriq.index')->with('success', 'Task status updated successfully.');
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
        $statuses = [
            'sent' => ['label' => 'Sent', 'color' => 'blue'],
            'opened' => ['label' => 'Opened', 'color' => 'orange'],
            'done' => ['label' => 'Done', 'color' => 'green'],
            'rejected' => ['label' => 'Rejected', 'color' => 'red'],
            'approved' => ['label' => 'Approved', 'color' => 'purple'],
        ];
        return view('topshiriq.edit' , compact('hududs' , 'categories' , 'topshiriq' , 'statuses'));
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
            'status' => 'nullable|string', // Validation for status
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
        
        // Update the task itself
        $task->update($request->only('title', 'category_id', 'ijrochi', 'muddat'));
        
        // Update the status in the related hudud_topshiriqs table
        if ($request->has('status')) {
            // Find the related hudud_topshiriq entry and update the status
            $hududTopshiriq = HududTopshiriq::where('topshiriq_id', $id)->first();
            if ($hududTopshiriq) {
                $hududTopshiriq->status = $request->status;
                $hududTopshiriq->save();
            }
        }
        
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
    // public function status($id, $status)
    // {
    //     // Find the task by ID
    //     $task = Topshiriq::findOrFail($id);

    //     // Update the status of the task
    //     // You can customize this logic to check for specific conditions based on your status logic
    //     $task->hududTopshiriqs->each(function ($hududTopshiriq) use ($status) {
    //         $hududTopshiriq->update(['status' => $status]);
    //     });

    //     // Redirect back with success message
    //     return redirect()->route('topshiriq.index')->with('success', 'Task status updated successfully.');
    // }

}
