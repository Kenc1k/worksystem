<!-- resources/views/topshiriq/index.blade.php -->
@extends('layouts.main')

@section('content')
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">{{$hududTopshiriq->where('status' , 'done')->count()}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            @forelse($messages as $message)
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">

                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ $message->user->name ?? 'Unknown User' }}
                                <span class="float-right text-sm text-{{ $message->priority == 'high' ? 'danger' : 'muted' }}">
                                    <i class="fas fa-star"></i>
                                </span>
                            </h3>
                            <p class="text-sm">{{ $message->content ?? 'No content available' }}</p>
                            <p class="text-sm text-muted">
                                <i class="far fa-clock mr-1"></i> {{ $message->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
            @empty
                <div class="dropdown-item text-center text-muted">
                    No messages available
                </div>
            @endforelse
            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
        
        
      </li>
    </ul>
</nav>
<br>
<div class="container mt-5">
    
    <div class="container-fluid">

    <div class="row mt-3">
        <div class="col-lg-3 col-6">
            <a href="{{ route('topshiriq.index') }}" class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistics['total'] }}</h3>
                    <p>Hammasi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </a>
            
        </div>
    
        <div class="col-lg-3 col-6">
            <a href="{{ route('topshiriq.index', ['from_date' => now()->addDays(2)->toDateString(), 'to_date' => now()->addDays(2)->toDateString()]) }}" class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistics['twoDaysLater'] }}</h3>
                    <p>2 kun qolgan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </a>
        </div>
    
        <div class="col-lg-3 col-6">
            <a href="{{ route('topshiriq.index', ['from_date' => now()->addDay()->toDateString(), 'to_date' => now()->addDay()->toDateString()]) }}" class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistics['tomorrow'] }}</h3>
                    <p>Ertaga</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </a>
        </div>
    
        <div class="col-lg-3 col-6">
            <a href="{{ route('topshiriq.index', ['status' => 'rejected']) }}" class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $statistics['rejected'] }}</h3>
                    <p>Qaytarilgan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </a>
        </div>
    </div>
    

    <!-- Header and Create Button -->
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3">Topshiriqlar ro'yxati</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('topshiriq.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yangi topshiriq
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('topshiriq.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label for="from_date">Boshlanish sanasi:</label>
                        <input type="date" id="from_date" name="from_date" 
                               class="form-control" 
                               value="{{ request('from_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label for="to_date">Tugash sanasi:</label>
                        <input type="date" id="to_date" name="to_date" 
                               class="form-control" 
                               value="{{ request('to_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Filterlash
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Sarlavha</th>
                            <th>Kategoriya</th>
                            <th>Hudud</th>
                            <th>Ijrochi</th>
                            <th>Fayl</th>
                            <th>Muddat</th>
                            <th>Status</th>
                            <th>Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->category->name ?? 'N/A' }}</td>
                                <td>
                                    @foreach($task->hududTopshiriqs as $hududTopshiriq)
                                        <div class="mb-1">
                                            <span class="badge badge-info">
                                                {{ $hududTopshiriq->hudud->name ?? 'N/A' }}
                                            </span>
                                            <span class="badge {{ $hududTopshiriq->status_badge_class }}">
                                                {{ $hududTopshiriq->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ $task->ijrochi }}</td>
                                <td class="text-center">
                                    @if($task->file)
                                        <a href="{{ asset('storage/' . $task->file) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-secondary">Fayl yo'q</span>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($task->muddat)->format('d.m.Y') }}
                                    @if(\Carbon\Carbon::parse($task->muddat)->isPast())
                                        <span class="badge badge-danger">Muddat o'tgan</span>
                                    @elseif(\Carbon\Carbon::parse($task->muddat)->isToday())
                                        <span class="badge badge-warning">Bugun</span>
                                    @elseif(\Carbon\Carbon::parse($task->muddat)->isTomorrow())
                                        <span class="badge badge-info">Ertaga</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($task->hududTopshiriqs as $hududTopshiriq)
                                        <span class="badge {{ $hududTopshiriq->status_badge_class }}">
                                            {{ $hududTopshiriq->status }}
                                        </span>
                                    @endforeach
                                </td>
                                
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('topshiriq.edit', $task->id) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('topshiriq.destroy', $task->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Rostdan ham o\'chirmoqchimisiz?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Topshiriqlar topilmadi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>

@push('styles')
<style>
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .small-box .inner {
        padding: 10px;
    }
    
    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 50px;
        opacity: 0.3;
    }
    
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }
    
    .small-box p {
        font-size: 1rem;
        margin-bottom: 0;
    }
</style>
@endpush
@endsection