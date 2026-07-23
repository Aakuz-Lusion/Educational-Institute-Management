<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of sections.
     */
    public function index()
    {
        $sections = Section::with('class')->get();
        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        $classes = Classes::all();
        return view('admin.sections.create', compact('classes'));
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name'     => 'required|string|max:255|unique:sections,name,NULL,id,class_id,' . $request->class_id,
        ]);

        Section::create($request->all());

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section added successfully.');
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section)
    {
        $classes = Classes::all();
        return view('admin.sections.edit', compact('section', 'classes'));
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name'     => 'required|string|max:255|unique:sections,name,' . $section->id . ',id,class_id,' . $request->class_id,
        ]);

        $section->update($request->all());

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified section.
     */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}
