@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Class: {{ $class->name }}</h1>
    <form method="POST" action="{{ route('admin.classes.update', $class) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Class Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $class->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Class</button>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection