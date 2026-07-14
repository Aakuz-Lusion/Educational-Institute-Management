<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with(['studentProfile', 'teacherProfile', 'staffProfile'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $classes = Classes::all();
        $sections = Section::all();
        return view('admin.users.create', compact('classes', 'sections'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,teacher,student,staff',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'dob'      => 'nullable|date|before:today',
            'class_id' => 'required_if:role,student|exists:classes,id',
            'section_id' => 'required_if:role,student|exists:sections,id',
        ]);

        // Create the user
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        // Create profile based on role
        if ($user->role === 'student') {
            Student::create([
                'user_id'    => $user->id,
                'class_id'   => $request->class_id,
                'section_id' => $request->section_id,
                'phone'      => $request->phone,
                'address'    => $request->address,
                'dob'        => $request->dob,
            ]);
        } elseif ($user->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);
        } elseif ($user->role === 'staff') {
            Staff::create([
                'user_id' => $user->id,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $classes = Classes::all();
        $sections = Section::all();
        return view('admin.users.edit', compact('user', 'classes', 'sections'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,teacher,student,staff',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'dob'      => 'nullable|date|before:today',
            'class_id' => 'required_if:role,student|exists:classes,id',
            'section_id' => 'required_if:role,student|exists:sections,id',
        ]);

        // Update basic user info
        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ]);

        // Update or create profile based on role
        if ($user->role === 'student') {
            // Delete any non-student profiles
            $user->teacherProfile()->delete();
            $user->staffProfile()->delete();

            $user->studentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'class_id'   => $request->class_id,
                    'section_id' => $request->section_id,
                    'phone'      => $request->phone,
                    'address'    => $request->address,
                    'dob'        => $request->dob,
                ]
            );
        } elseif ($user->role === 'teacher') {
            $user->studentProfile()->delete();
            $user->staffProfile()->delete();

            $user->teacherProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'   => $request->phone,
                    'address' => $request->address,
                ]
            );
        } elseif ($user->role === 'staff') {
            $user->studentProfile()->delete();
            $user->teacherProfile()->delete();

            $user->staffProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'   => $request->phone,
                    'address' => $request->address,
                ]
            );
        } else { // admin – no profile needed, delete any existing
            $user->studentProfile()->delete();
            $user->teacherProfile()->delete();
            $user->staffProfile()->delete();
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Optionally use soft deletes – but we'll just delete
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }

    /**
     * Show the form to change password.
     */
    public function editPassword(User $user)
    {
        return view('admin.users.edit-password', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Password updated successfully.');
    }
}