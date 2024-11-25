@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Dynamic Small Boxes -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
              <div class="inner">
                  <h3>{{ $totalTasks }}</h3>
                  <p>All</p>
              </div>
              <div class="icon">
                  <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('admin.index', ['filter' => 'all']) }}" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>
      
      <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
              <div class="inner">
                  <h3>{{ $twoDaysRemaining }}</h3>
                  <p>2 kun qolgan</p>
              </div>
              <div class="icon">
                  <i class="ion ion-stats-bars"></i>
              </div>
              <a href="{{ route('admin.index', ['filter' => 'two_days_remaining']) }}" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>
      
      <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
              <div class="inner">
                  <h3>{{ $oneDayRemaining }}</h3>
                  <p>1 kun qolgan</p>
              </div>
              <div class="icon">
                  <i class="ion ion-person-add"></i>
              </div>
              <a href="{{ route('admin.index', ['filter' => 'one_day_remaining']) }}" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>
      
      <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
              <div class="inner">
                  <h3>{{ $notCompleted }}</h3>
                  <p>Bajarilmagan</p>
              </div>
              <div class="icon">
                  <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('admin.index', ['filter' => 'not_completed']) }}" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>
      
      <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
              <div class="inner">
                  <h3>{{ $completed }}</h3>
                  <p>Bajarilgan</p>
              </div>
              <div class="icon">
                  <i class="ion ion-checkmark"></i>
              </div>
              <a href="{{ route('admin.index', ['filter' => 'completed']) }}" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>
      
    </div>
</div>

<!-- Table Section -->
<div class="container mt-5">
    <table class="table table-hover table-striped table-bordered" style="border-radius: 10px; overflow: hidden; border-collapse: separate;">
        <thead class="thead-dark">
            <tr>
                <th scope="col" style="background: #343a40; color: white; text-align: center;">Hududlar</th>
                @foreach ($categories as $category)
                    <th style="background: #6c757d; color: white; text-align: center;">{{ $category->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($hududs as $hudud)
                <tr>
                    <td style="text-align: center; font-weight: bold; background-color: black; color: white;">
                        {{ $hudud->name }}
                    </td>
                    @foreach ($categories as $category)
                        @php
                            $topshiriq = $hudud->topshiriqs->where('category_id', $category->id)->first();
                        @endphp
                        <td style="text-align: center;">
                            @if ($topshiriq)
                                <span class="badge bg-{{ $statuses[$topshiriq->pivot->status] ?? 'secondary' }}">
                                    {{ ucfirst($topshiriq->pivot->status) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
