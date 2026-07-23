<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\Schedule;

class ScheduleGenerator
{
    protected $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    protected $periods = [1, 2, 4, 5, 7, 8, 10];
    protected $grades = [
        'Grade 1-A', 'Grade 1-B', 'Grade 2-A', 'Grade 2-B',
        'Grade 3-A', 'Grade 3-B', 'Grade 4-A', 'Grade 4-B',
        'Grade 5-A', 'Grade 5-B'
    ];

    public function generate()
    {
        Schedule::truncate();
        $teachers = Teacher::where('is_available', true)->get();
        $total = 0;
        $errors = [];

        foreach ($this->grades as $grade) {
            $gradeTeachers = $teachers->where('grade', $grade);

            foreach ($this->days as $day) {
                $availableTeachers = $gradeTeachers->filter(function($teacher) use ($day) {
                    $days = $this->decodeJson($teacher->days);
                    return in_array($day, $days);
                });

                foreach ($this->periods as $period) {
                    $teacher = $this->findTeacherForPeriod($availableTeachers, $period);

                    if ($teacher) {
                        Schedule::create([
                            'day' => $day,
                            'grade' => $grade,
                            'period_id' => $period,
                            'subject' => $teacher->subject,
                            'teacher_id' => $teacher->id,
                            'teacher_name' => $teacher->name,
                        ]);
                        $total++;
                    } else {
                        Schedule::create([
                            'day' => $day,
                            'grade' => $grade,
                            'period_id' => $period,
                            'subject' => 'unassigned',
                            'teacher_id' => null,
                            'teacher_name' => '—',
                        ]);
                    }
                }
            }
        }

        return ['total' => $total, 'errors' => $errors];
    }

    protected function findTeacherForPeriod($teachers, $period)
    {
        $priorityOrder = ['high', 'medium', 'low'];
        $available = $teachers->filter(function($teacher) use ($period) {
            $periods = $this->decodeJson($teacher->periods);
            return in_array($period, $periods);
        });

        if ($available->isEmpty()) {
            return null;
        }

        return $available->sortBy(function($teacher) use ($priorityOrder) {
            return array_search($teacher->priority, $priorityOrder);
        })->first();
    }

    protected function decodeJson($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}