<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    // app/Http/Controllers/Teacher/HomeworkController.php
    public function index()
    {
        $homeworks = Homework::where('teacher_id', auth()->id())->with('section')->paginate(10);
        return view('teacher.homeworks.index', compact('homeworks'));
    }

    public function create()
    {
                                              // Get sections assigned to this teacher
        $sections = auth()->user()->sections; // from relationship
        return view('teacher.homeworks.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'section_id'  => 'required|exists:sections,id',
            'due_date'    => 'required|date|after:today',
            'attachment'  => 'nullable|file|max:10240',
        ]);
        $homework             = new Homework($validated);
        $homework->teacher_id = auth()->id();
        if ($request->hasFile('attachment')) {
            $path                 = $request->file('attachment')->store('homeworks', 'public');
            $homework->attachment = $path;
        }
        $homework->save();
        return redirect()->route('teacher.homeworks.index')->with('success', 'Homework created.');
    }

    public function submissions(Homework $homework)
    {
        // Ensure teacher owns this homework
        if ($homework->teacher_id !== auth()->id()) {
            abort(403);
        }

        $submissions = $homework->submissions()->with('student')->get();
        return view('teacher.homeworks.submissions', compact('homework', 'submissions'));
    }

    public function grade(Request $request, Submission $submission)
    {
        $request->validate(['grade' => 'required|numeric|min:0|max:100']);
        $submission->update(['grade' => $request->grade]);
        return back()->with('success', 'Grade updated.');
    }
}
