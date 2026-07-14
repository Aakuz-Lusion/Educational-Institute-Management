@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Sections</h1>
        <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">Add New Section</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Class</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sections as $section)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $section->name }}</td>
                <td>{{ $section->class->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">No sections found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection