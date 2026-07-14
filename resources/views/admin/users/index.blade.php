@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Profile</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-info">{{ ucfirst($user->role) }}</span></td>
                <td>
                    @if($user->role === 'student' && $user->studentProfile)
                    Class: {{ $user->studentProfile->class->name ?? 'N/A' }},
                    Section: {{ $user->studentProfile->section->name ?? 'N/A' }}
                    @elseif($user->role === 'teacher' && $user->teacherProfile)
                    Phone: {{ $user->teacherProfile->phone ?? 'N/A' }}
                    @elseif($user->role === 'staff' && $user->staffProfile)
                    Phone: {{ $user->staffProfile->phone ?? 'N/A' }}
                    @else
                    Admin
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('admin.users.edit-password', $user) }}" class="btn btn-sm btn-info">Change
                        Password</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection