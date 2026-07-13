<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\Submission;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function index()
    {
        $student   = auth()->user()->studentProfile;
        $homeworks = Homework::where('section_id', $student->section_id)->with('teacher')->paginate(10);

        return view('student.homeworks.index', compact('homeworks'));
    }

    public function show(Homework $homework)
    {
        $submission = Submission::where('homework_id', $homework->id)
            ->where('student_id', auth()->id())
            ->first();

        return view('student.homeworks.show', compact('homework', 'submission'));
    }

    public function submit(Request $request, Homework $homework)
    {
        $request->validate([
            'file'    => 'required|file|max:5120',
            'comment' => 'nullable|string',
        ]);

        if (Submission::where('homework_id', $homework->id)->where('student_id', auth()->id())->exists()) {
            return back()->with('error', 'You already submitted.');
        }

        $path = $request->file('file')->store('submissions', 'public');

        Submission::create([
            'homework_id'  => $homework->id,
            'student_id'   => auth()->id(),
            'file_path'    => $path,
            'comment'      => $request->comment,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.homeworks.show', $homework)->with('success', 'Submitted.');
    }
}