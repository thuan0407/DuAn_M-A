@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="row g-4">

    <div class="col-md-4">
        <div class="card p-4 shadow-sm rounded-4">
            <h6 class="text-muted">Công ty</h6>
            <h3>{{ $companies }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 shadow-sm rounded-4">
            <h6 class="text-muted">Deals</h6>
            <h3>{{ $deals }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 shadow-sm rounded-4">
            <h6 class="text-muted">Người dùng</h6>
            <h3>{{ $users }}</h3>
        </div>
    </div>

</div>

@endsection
