<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/Admin/ClassController.php
    public function index()
    {
        $classes = Classes::with('sections')->get();
        return view('admin.classes.index', compact('classes'));
    }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:classes']);
        Classes::create($request->all());
        return back()->with('success', 'Class added.');
    }
    public function destroy(Classes $class)
    {
        $class->delete();
        return back()->with('success', 'Class deleted.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
}
