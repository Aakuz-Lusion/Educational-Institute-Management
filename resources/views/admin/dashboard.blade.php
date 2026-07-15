@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Welcome, {{ auth()->user()->name }}!</h1>

    <!-- Stats cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="display-4">{{ \App\Models\Student::count() }}</div>
                    <div class="h6">Students</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="display-4">{{ \App\Models\Teacher::count() }}</div>
                    <div class="h6">Teachers</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="display-4">{{ \App\Models\User::count() }}</div>
                    <div class="h6">Total Users</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="display-4">{{ \App\Models\FeeInvoice::where('status', 'pending')->count() }}</div>
                    <div class="h6">Pending Invoices</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional info -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Quick Links</div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @if(Route::has('admin.users.create'))
                            <li><a href="{{ route('admin.users.create') }}"><i class="fas fa-user-plus"></i> Add New User</a></li>
                        @endif
                        @if(Route::has('admin.classes.create'))
                            <li><a href="{{ route('admin.classes.create') }}"><i class="fas fa-plus-circle"></i> Add New Class</a></li>
                        @endif
                        @if(Route::has('admin.sections.create'))
                            <li><a href="{{ route('admin.sections.create') }}"><i class="fas fa-plus-circle"></i> Add New Section</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">System Info</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Logged in as</span>
                            <span class="badge bg-success">{{ auth()->user()->name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection