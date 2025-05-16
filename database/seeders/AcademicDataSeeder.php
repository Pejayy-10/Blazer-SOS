<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed colleges
        $colleges = [
            [
                'name' => 'College of Engineering',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'College of Business Administration',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'College of Arts and Sciences',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'College of Education',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'College of Computer Studies',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($colleges as $college) {
            DB::table('colleges')->updateOrInsert(
                ['name' => $college['name']],
                $college
            );
        }
        $this->command->info('Colleges seeded successfully');

        // Get all college IDs for reference in courses
        $collegeIds = DB::table('colleges')->pluck('id', 'name')->toArray();

        // Seed courses
        $courses = [
            // Engineering courses
            [
                'name' => 'Bachelor of Science in Civil Engineering',
                'abbreviation' => 'BSCE',
                'college_id' => $collegeIds['College of Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Science in Mechanical Engineering',
                'abbreviation' => 'BSME',
                'college_id' => $collegeIds['College of Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Science in Electrical Engineering',
                'abbreviation' => 'BSEE',
                'college_id' => $collegeIds['College of Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Business Administration courses
            [
                'name' => 'Bachelor of Science in Business Administration',
                'abbreviation' => 'BSBA',
                'college_id' => $collegeIds['College of Business Administration'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Science in Accounting',
                'abbreviation' => 'BSA',
                'college_id' => $collegeIds['College of Business Administration'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Arts and Sciences courses
            [
                'name' => 'Bachelor of Science in Psychology',
                'abbreviation' => 'BSP',
                'college_id' => $collegeIds['College of Arts and Sciences'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Arts in Communication',
                'abbreviation' => 'BAC',
                'college_id' => $collegeIds['College of Arts and Sciences'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Education courses
            [
                'name' => 'Bachelor of Elementary Education',
                'abbreviation' => 'BEEd',
                'college_id' => $collegeIds['College of Education'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Secondary Education',
                'abbreviation' => 'BSEd',
                'college_id' => $collegeIds['College of Education'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Computer Studies courses
            [
                'name' => 'Bachelor of Science in Computer Science',
                'abbreviation' => 'BSCS',
                'college_id' => $collegeIds['College of Computer Studies'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Science in Information Technology',
                'abbreviation' => 'BSIT',
                'college_id' => $collegeIds['College of Computer Studies'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bachelor of Science in Information Systems',
                'abbreviation' => 'BSIS',
                'college_id' => $collegeIds['College of Computer Studies'],
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($courses as $course) {
            DB::table('courses')->updateOrInsert(
                ['name' => $course['name'], 'college_id' => $course['college_id']],
                $course
            );
        }
        $this->command->info('Courses seeded successfully');

        // Get all course IDs for reference in majors
        $courseIds = DB::table('courses')->pluck('id', 'name')->toArray();

        // Seed majors
        $majors = [
            // Engineering majors
            [
                'name' => 'Structural Engineering',
                'course_id' => $courseIds['Bachelor of Science in Civil Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Geotechnical Engineering',
                'course_id' => $courseIds['Bachelor of Science in Civil Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Thermodynamics',
                'course_id' => $courseIds['Bachelor of Science in Mechanical Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Robotics and Automation',
                'course_id' => $courseIds['Bachelor of Science in Mechanical Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Power Systems',
                'course_id' => $courseIds['Bachelor of Science in Electrical Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Telecommunications',
                'course_id' => $courseIds['Bachelor of Science in Electrical Engineering'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Business Administration majors
            [
                'name' => 'Marketing Management',
                'course_id' => $courseIds['Bachelor of Science in Business Administration'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Financial Management',
                'course_id' => $courseIds['Bachelor of Science in Business Administration'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'General Accounting',
                'course_id' => $courseIds['Bachelor of Science in Accounting'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Public Accounting',
                'course_id' => $courseIds['Bachelor of Science in Accounting'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Arts and Sciences majors
            [
                'name' => 'Clinical Psychology',
                'course_id' => $courseIds['Bachelor of Science in Psychology'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Industrial Psychology',
                'course_id' => $courseIds['Bachelor of Science in Psychology'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mass Communication',
                'course_id' => $courseIds['Bachelor of Arts in Communication'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Public Relations',
                'course_id' => $courseIds['Bachelor of Arts in Communication'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Education majors
            [
                'name' => 'Elementary Mathematics',
                'course_id' => $courseIds['Bachelor of Elementary Education'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Special Education',
                'course_id' => $courseIds['Bachelor of Elementary Education'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mathematics',
                'course_id' => $courseIds['Bachelor of Secondary Education'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'English',
                'course_id' => $courseIds['Bachelor of Secondary Education'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Computer Studies majors
            [
                'name' => 'Artificial Intelligence',
                'course_id' => $courseIds['Bachelor of Science in Computer Science'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Data Science',
                'course_id' => $courseIds['Bachelor of Science in Computer Science'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Network Administration',
                'course_id' => $courseIds['Bachelor of Science in Information Technology'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Web Development',
                'course_id' => $courseIds['Bachelor of Science in Information Technology'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Enterprise Systems',
                'course_id' => $courseIds['Bachelor of Science in Information Systems'],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Business Intelligence',
                'course_id' => $courseIds['Bachelor of Science in Information Systems'],
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($majors as $major) {
            DB::table('majors')->updateOrInsert(
                ['name' => $major['name'], 'course_id' => $major['course_id']],
                $major
            );
        }
        $this->command->info('Majors seeded successfully');
    }
}
