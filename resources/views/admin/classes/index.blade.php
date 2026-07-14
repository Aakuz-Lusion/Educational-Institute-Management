@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Classes</h1>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Add New Class</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($classes->isEmpty())
        <div class="alert alert-info">No classes found. Click "Add New Class" to create one.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Sections</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $class)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $class->name }}</td>
                    <td>
                        @if($class->sections->isNotEmpty())
                            @foreach($class->sections as $section)
                                <span class="badge bg-secondary">{{ $section->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No sections</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this class?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection