@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User: {{ $user->name }}</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="admin" {{ old('role', $user->role)=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="teacher" {{ old('role', $user->role)=='teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="student" {{ old('role', $user->role)=='student' ? 'selected' : '' }}>Student</option>
                <option value="staff" {{ old('role', $user->role)=='staff' ? 'selected' : '' }}>Staff</option>
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Student fields -->
        <div id="studentFields" style="{{ old('role', $user->role) == 'student' ? '' : 'display:none;' }}">
            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-control @error('class_id') is-invalid @enderror" id="class_id" name="class_id">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $user->studentProfile->class_id ?? '') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="section_id" class="form-label">Section</label>
                <select class="form-control @error('section_id') is-invalid @enderror" id="section_id" name="section_id">
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ old('section_id', $user->studentProfile->section_id ?? '') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob', $user->studentProfile->dob ?? '') }}">
                @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->studentProfile->phone ?? $user->teacherProfile->phone ?? $user->staffProfile->phone ?? '') }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $user->studentProfile->address ?? $user->teacherProfile->address ?? $user->staffProfile->address ?? '') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('role').addEventListener('change', function() {
        const studentFields = document.getElementById('studentFields');
        if (this.value === 'student') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    });
</script>
@endpush
@endsection