<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Course;
use Illuminate\Database\Seeder;

class AcademicOptionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'B.Tech' => [
                'Computer Science & Engineering',
                'Information Technology',
                'Artificial Intelligence & Machine Learning',
                'Electronics & Communication Engineering',
                'Mechanical Engineering',
                'Civil Engineering',
                'Electrical Engineering',
            ],
            'MCA' => ['Computer Applications'],
            'BCA' => ['Computer Applications'],
            'M.Tech' => ['Computer Science & Engineering'],
            'MBA' => ['General Management'],
            'BBA' => ['General Management'],
            'B.Sc' => ['Computer Science', 'Information Technology'],
            'B.Com' => ['Commerce'],
            'Diploma' => ['Computer Science & Engineering', 'Information Technology'],
        ];

        foreach ($data as $courseName => $branches) {
            $course = Course::withTrashed()->firstOrCreate(
                ['name' => $courseName],
                ['status' => true]
            );

            if ($course->trashed()) {
                $course->restore();
                $course->update(['status' => true]);
            }

            foreach ($branches as $branchName) {
                $branch = Branch::withTrashed()->firstOrCreate(
                    ['course_id' => $course->id, 'name' => $branchName],
                    ['status' => true]
                );

                if ($branch->trashed()) {
                    $branch->restore();
                    $branch->update(['status' => true]);
                }
            }
        }
    }
}
