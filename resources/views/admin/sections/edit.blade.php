@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Section: {{ $section->name }}</h1>
    <form method="POST" action="{{ route('admin.sections.update', $section) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <select class="form-control @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ old('class_id', $section->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Section Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $section->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Section</button>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection