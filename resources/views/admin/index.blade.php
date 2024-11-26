@extends('layouts.main')

@section('content')

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
