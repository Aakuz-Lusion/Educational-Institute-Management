@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text display-4">{{ \App\Models\Student::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Teachers</h5>
                    <p class="card-text display-4">{{ \App\Models\Teacher::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-4">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Invoices</h5>
                    <p class="card-text display-4">{{ \App\Models\FeeInvoice::where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tasks"></i> Quick Actions
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(Route::has('admin.users.index'))
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-block mb-2">Manage Users</a>
                        </div>
                        @endif

                        @if(Route::has('admin.classes.index'))
                        <div class="col-md-6">
                            <a href="{{ route('admin.classes.index') }}" class="btn btn-success btn-block mb-2">Manage Classes</a>
                        </div>
                        @endif

                        @if(Route::has('admin.fee-structures.index'))
                        <div class="col-md-6">
                            <a href="{{ route('admin.fee-structures.index') }}" class="btn btn-warning btn-block mb-2">Manage Fees</a>
                        </div>
                        @endif

                        @if(Route::has('admin.invoices.index'))
                        <div class="col-md-6">
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-info btn-block mb-2">View Invoices</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> System Info
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Laravel Version
                            <span class="badge bg-primary rounded-pill">{{ app()->version() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            PHP Version
                            <span class="badge bg-primary rounded-pill">{{ phpversion() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Logged in as
                            <span class="badge bg-success rounded-pill">{{ auth()->user()->name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection