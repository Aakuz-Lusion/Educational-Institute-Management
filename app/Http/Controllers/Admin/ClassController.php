<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     */
    public function index()
    {
        $classes = Classes::with('sections')->get();
        return view('admin.classes.index', compact('classes'));
    }

    /**
     */
    public function create()
    {
        return view('admin.classes.create');
    }

    /**
     *
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:classes,name',
            ]);

            $class = Classes::create($request->all());

            return redirect()->route('admin.classes.index')
                ->with('success', 'Class added successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTrace()); // dump the error and trace
        }
    }

    /**
     */
    public function edit(Classes $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    /**
     */
    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
        ]);

        $class->update($request->all());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     */
    public function destroy(Classes $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
