@extends('layouts.main')


@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Hisobotlar</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered text-center table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>№</th>
                                    <th>Hududlar</th>
                                    <th>Status</th>
                                    @foreach($hududs as $hudud)
                                        <th style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                            {{$hudud->name}}
                                        </th>
                                    @endforeach
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topshiriqs as $topshiriq)
                                    <tr>
                                        <th>{{$topshiriq->id}}</th>
                                        <td>{{$topshiriq->name}}</td>
                                        <td>
                                            <table class="table table-borderless">
                                                <tr><td>Approved</td></tr>
                                                <tr><td>Jarayonda</td></tr>
                                                <tr><td>Ko'rilgan</td></tr>
                                                <tr><td>Sent</td></tr>
                                                <tr><td>Rejected</td></tr>
                                                <tr><td>Muddat buzilgan</td></tr>
                                            </table>
                                        </td>
                                        @foreach($hududs as $hudud)
                                            <td>
                                                <table class="table table-borderless">
                                                    @foreach([4 => 'success', 3 => 'primary', 2 => 'warning', 1 => 'info', 0 => 'secondary'] as $status => $color)
                                                        <tr>
                                                            <td>
                                                                <button class="btn btn-{{$color}} btn-sm">
                                                                    {{$topshiriq->hududTopshiriq->where('hudud_id', $hudud->id)->where('status', $status)->count()}}
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm">
                                                                {{$topshiriq->hududTopshiriq->where('hudud_id', $hudud->id)->where('period', '<', date('Y-m-d'))->where('status', '!=', 'approved')->count()}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        @endforeach
                                        <td>
                                            <table class="table table-borderless">
                                                @foreach([4 => 'success', 3 => 'primary', 2 => 'warning', 1 => 'info', 0 => 'secondary'] as $status => $color)
                                                    <tr>
                                                        <td>
                                                            <button class="btn btn-{{$color}} btn-sm">
                                                                {{$topshiriq->hududTopshiriq->where('status', $status)->count()}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            {{$topshiriq->hududTopshiriq->where('period', '<', date('Y-m-d'))->where('status', '!=', 'approved')->count()}}
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
