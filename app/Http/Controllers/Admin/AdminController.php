<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Services\ScheduleGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $teachers = Teacher::all();
        $availableTeachers = Teacher::where('is_available', true)->count();
        $unavailableTeachers = Teacher::where('is_available', false)->count();
        $totalSchedules = Schedule::count();

        return view('admin.dashboard', compact(
            'teachers',
            'availableTeachers',
            'unavailableTeachers',
            'totalSchedules'
        ));
    }

    public function schedule()
    {
        return view('admin.schedule');
    }

    public function showChangePassword($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.change-password', compact('teacher'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->password = Hash::make($request->password);
        $teacher->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Password changed successfully for ' . $teacher->name);
    }

    public function generateSchedule()
    {
        try {
            $generator = new ScheduleGenerator();
            $result = $generator->generate();

            if ($result['total'] > 0) {
                $message = "Schedule generated successfully! {$result['total']} classes assigned.";
                if (!empty($result['errors'])) {
                    $message .= ' (Note: ' . implode(', ', $result['errors']) . ')';
                }
                return redirect()->route('admin.dashboard')
                    ->with('success', $message);
            } else {
                $errorMsg = !empty($result['errors']) ? implode(', ', $result['errors']) : 'No teachers available to assign';
                return redirect()->route('admin.dashboard')
                    ->with('error', "Schedule generation failed: {$errorMsg}");
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Error generating schedule: ' . $e->getMessage());
        }
    }

    public function showReplacement($id)
    {
        $unavailableTeacher = Teacher::findOrFail($id);
        
        $availableTeachers = Teacher::where('is_available', true)
            ->where('id', '!=', $id)
            ->where('subject', $unavailableTeacher->subject)
            ->get();

        if ($availableTeachers->isEmpty()) {
            $availableTeachers = Teacher::where('is_available', true)
                ->where('id', '!=', $id)
                ->limit(5)
                ->get();
        }

        return view('admin.replacement', compact('unavailableTeacher', 'availableTeachers'));
    }

    public function assignReplacement(Request $request, $id)
    {
        $request->validate([
            'replacement_teacher_id' => 'required|exists:teachers,id',
            'reason' => 'nullable|string|max:255',
        ]);

        $originalTeacher = Teacher::findOrFail($id);
        $replacementTeacher = Teacher::findOrFail($request->replacement_teacher_id);

        $updatedCount = Schedule::where('teacher_id', $id)
            ->where('day', now()->format('l'))
            ->update([
                'teacher_id' => $replacementTeacher->id,
                'teacher_name' => $replacementTeacher->name,
                'subject' => $replacementTeacher->subject,
            ]);

        $originalTeacher->is_available = false;
        $originalTeacher->unavailable_date = now()->toDateString();
        $originalTeacher->unavailable_reason = $request->reason ?? 'Replacement assigned';
        $originalTeacher->save();

        $message = "Replacement assigned successfully! {$updatedCount} schedule(s) updated.";
        return redirect()->route('admin.dashboard')
            ->with('success', $message);
    }

    public function generateGradeSchedule($grade)
    {
        try {
            $generator = new ScheduleGenerator();
            $result = $generator->generateForGrade($grade);

            if ($result['total'] > 0) {
                return redirect()->route('admin.schedule')
                    ->with('success', "Schedule generated for {$grade}! {$result['total']} classes assigned.");
            } else {
                return redirect()->route('admin.schedule')
                    ->with('error', "No teachers available for {$grade}");
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.schedule')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function clearSchedules()
    {
        $count = Schedule::count();
        Schedule::truncate();

        return redirect()->route('admin.dashboard')
            ->with('info', "All schedules cleared. ({$count} entries removed)");
    }

    public function scheduleStats()
    {
        $total = Schedule::count();
        $assigned = Schedule::where('teacher_name', '!=', '—')->count();
        $unassigned = Schedule::where('teacher_name', '—')->count();

        return response()->json([
            'total' => $total,
            'assigned' => $assigned,
            'unassigned' => $unassigned,
            'coverage' => $total > 0 ? round(($assigned / $total) * 100, 2) : 0
        ]);
    }
}