@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Manage Classes</h1>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Add Class</a>
    <table class="table table-bordered mt-3">
        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($classes as $class)
            <tr>
                <td>{{ $class->id }}</td>
                <td>{{ $class->name }}</td>
                <td>
                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection