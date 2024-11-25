<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Hudud;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::all();
    $hududs = Hudud::with(['topshiriqs'])->get();

    $totalTasks = $hududs->pluck('topshiriqs')->flatten()->count();
    $twoDaysRemaining = $hududs->pluck('topshiriqs')->flatten()->where('deadline', now()->addDays(2))->count();
    $oneDayRemaining = $hududs->pluck('topshiriqs')->flatten()->where('deadline', now()->addDay())->count();
    $notCompleted = $hududs->pluck('topshiriqs')->flatten()->where('status', '!=', 'done')->count();
    $completed = $hududs->pluck('topshiriqs')->flatten()->where('status', 'done')->count();

    $statuses = [
        'sent' => 'info',
        'opened' => 'primary',
        'done' => 'success',
        'rejected' => 'danger',
        'approved' => 'warning',
    ];

    $filter = $request->query('filter');
    if ($filter) {
        $hududs = $hududs->filter(function ($hudud) use ($filter) {
            $filteredTopshiriqs = $hudud->topshiriqs->filter(function ($topshiriq) use ($filter) {
                switch ($filter) {
                    case 'two_days_remaining':
                        return $topshiriq->deadline == now()->addDays(2);
                    case 'one_day_remaining':
                        return $topshiriq->deadline == now()->addDay();
                    case 'not_completed':
                        return $topshiriq->status !== 'done';
                    case 'completed':
                        return $topshiriq->status === 'done';
                    default:
                        return true;
                }
            });
            return $filteredTopshiriqs->isNotEmpty();
        });
    }

    return view('admin.index', compact(
        'categories',
        'hududs',
        'statuses',
        'totalTasks',
        'twoDaysRemaining',
        'oneDayRemaining',
        'notCompleted',
        'completed'
    ));
}

}
