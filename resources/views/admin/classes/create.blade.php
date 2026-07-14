@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Class</h1>
    <form method="POST" action="{{ route('admin.classes.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Class Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Save Class</button>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection