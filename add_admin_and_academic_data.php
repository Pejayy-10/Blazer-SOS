<?php

require __DIR__.'/vendor/autoload.php';

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    echo "=== Creating Regular Admin and Academic Data ===\n\n";

    // Check if the Admin role exists, if not create it
    $adminRole = \DB::table('role_names')->where('name', 'Admin')->first();
    
    if (!$adminRole) {
        $adminRoleId = \DB::table('role_names')->insertGetId([
            'name' => 'Admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Created 'Admin' role with ID: $adminRoleId\n";
    } else {
        $adminRoleId = $adminRole->id;
        echo "Found existing 'Admin' role with ID: $adminRoleId\n";
    }

    // Create a regular admin user
    $existingAdmin = \DB::table('users')->where('email', 'admin@example.com')->first();
    
    if (!$existingAdmin) {
        $adminId = \DB::table('users')->insertGetId([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'first_name' => 'Regular',
            'last_name' => 'Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
            'role_name' => 'Admin',
            'role_name_id' => $adminRoleId,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Created regular admin user with ID: $adminId\n";
    } else {
        echo "Regular admin user already exists with ID: {$existingAdmin->id}\n";
        
        // Update the user's role if needed
        if ($existingAdmin->role_name_id != $adminRoleId) {
            \DB::table('users')->where('id', $existingAdmin->id)->update([
                'role' => 'admin',
                'role_name' => 'Admin',
                'role_name_id' => $adminRoleId,
                'updated_at' => now()
            ]);
            echo "Updated regular admin user's role\n";
        }
    }

    echo "\n=== Populating Academic Data ===\n\n";

    // Seed Colleges
    $colleges = [
        ['name' => 'College of Engineering'],
        ['name' => 'College of Business Administration'],
        ['name' => 'College of Arts and Sciences'],
        ['name' => 'College of Education'],
        ['name' => 'College of Computer Studies']
    ];

    echo "Adding colleges...\n";
    foreach ($colleges as $college) {
        $exists = \DB::table('colleges')->where('name', $college['name'])->exists();
        
        if (!$exists) {
            $id = \DB::table('colleges')->insertGetId([
                'name' => $college['name'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "  - Added {$college['name']} with ID: $id\n";
        } else {
            echo "  - {$college['name']} already exists\n";
        }
    }

    // Get all college IDs
    $collegeIds = \DB::table('colleges')->pluck('id', 'name')->toArray();
    
    // Define courses
    $courses = [
        // Engineering courses
        [
            'name' => 'Bachelor of Science in Civil Engineering',
            'abbreviation' => 'BSCE',
            'college_id' => $collegeIds['College of Engineering'],
        ],
        [
            'name' => 'Bachelor of Science in Mechanical Engineering',
            'abbreviation' => 'BSME',
            'college_id' => $collegeIds['College of Engineering'],
        ],
        [
            'name' => 'Bachelor of Science in Electrical Engineering',
            'abbreviation' => 'BSEE',
            'college_id' => $collegeIds['College of Engineering'],
        ],
        
        // Business Administration courses
        [
            'name' => 'Bachelor of Science in Business Administration',
            'abbreviation' => 'BSBA',
            'college_id' => $collegeIds['College of Business Administration'],
        ],
        [
            'name' => 'Bachelor of Science in Accounting',
            'abbreviation' => 'BSA',
            'college_id' => $collegeIds['College of Business Administration'],
        ],
        
        // Arts and Sciences courses
        [
            'name' => 'Bachelor of Science in Psychology',
            'abbreviation' => 'BSP',
            'college_id' => $collegeIds['College of Arts and Sciences'],
        ],
        [
            'name' => 'Bachelor of Arts in Communication',
            'abbreviation' => 'BAC',
            'college_id' => $collegeIds['College of Arts and Sciences'],
        ],
        
        // Education courses
        [
            'name' => 'Bachelor of Elementary Education',
            'abbreviation' => 'BEEd',
            'college_id' => $collegeIds['College of Education'],
        ],
        [
            'name' => 'Bachelor of Secondary Education',
            'abbreviation' => 'BSEd',
            'college_id' => $collegeIds['College of Education'],
        ],
        
        // Computer Studies courses
        [
            'name' => 'Bachelor of Science in Computer Science',
            'abbreviation' => 'BSCS',
            'college_id' => $collegeIds['College of Computer Studies'],
        ],
        [
            'name' => 'Bachelor of Science in Information Technology',
            'abbreviation' => 'BSIT',
            'college_id' => $collegeIds['College of Computer Studies'],
        ],
        [
            'name' => 'Bachelor of Science in Information Systems',
            'abbreviation' => 'BSIS',
            'college_id' => $collegeIds['College of Computer Studies'],
        ]
    ];

    echo "\nAdding courses...\n";
    foreach ($courses as $course) {
        $exists = \DB::table('courses')
            ->where('name', $course['name'])
            ->where('college_id', $course['college_id'])
            ->exists();
        
        if (!$exists) {
            $id = \DB::table('courses')->insertGetId([
                'name' => $course['name'],
                'abbreviation' => $course['abbreviation'],
                'college_id' => $course['college_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "  - Added {$course['name']} with ID: $id\n";
        } else {
            echo "  - {$course['name']} already exists\n";
        }
    }

    // Get all course IDs
    $courseIds = \DB::table('courses')->pluck('id', 'name')->toArray();
    
    // Define majors
    $majors = [
        // Engineering majors
        [
            'name' => 'Structural Engineering',
            'course_id' => $courseIds['Bachelor of Science in Civil Engineering'],
        ],
        [
            'name' => 'Geotechnical Engineering',
            'course_id' => $courseIds['Bachelor of Science in Civil Engineering'],
        ],
        [
            'name' => 'Thermodynamics',
            'course_id' => $courseIds['Bachelor of Science in Mechanical Engineering'],
        ],
        [
            'name' => 'Robotics and Automation',
            'course_id' => $courseIds['Bachelor of Science in Mechanical Engineering'],
        ],
        [
            'name' => 'Power Systems',
            'course_id' => $courseIds['Bachelor of Science in Electrical Engineering'],
        ],
        [
            'name' => 'Telecommunications',
            'course_id' => $courseIds['Bachelor of Science in Electrical Engineering'],
        ],
        
        // Business Administration majors
        [
            'name' => 'Marketing Management',
            'course_id' => $courseIds['Bachelor of Science in Business Administration'],
        ],
        [
            'name' => 'Financial Management',
            'course_id' => $courseIds['Bachelor of Science in Business Administration'],
        ],
        [
            'name' => 'General Accounting',
            'course_id' => $courseIds['Bachelor of Science in Accounting'],
        ],
        [
            'name' => 'Public Accounting',
            'course_id' => $courseIds['Bachelor of Science in Accounting'],
        ],
        
        // Arts and Sciences majors
        [
            'name' => 'Clinical Psychology',
            'course_id' => $courseIds['Bachelor of Science in Psychology'],
        ],
        [
            'name' => 'Industrial Psychology',
            'course_id' => $courseIds['Bachelor of Science in Psychology'],
        ],
        [
            'name' => 'Mass Communication',
            'course_id' => $courseIds['Bachelor of Arts in Communication'],
        ],
        [
            'name' => 'Public Relations',
            'course_id' => $courseIds['Bachelor of Arts in Communication'],
        ],
        
        // Education majors
        [
            'name' => 'Elementary Mathematics',
            'course_id' => $courseIds['Bachelor of Elementary Education'],
        ],
        [
            'name' => 'Special Education',
            'course_id' => $courseIds['Bachelor of Elementary Education'],
        ],
        [
            'name' => 'Mathematics',
            'course_id' => $courseIds['Bachelor of Secondary Education'],
        ],
        [
            'name' => 'English',
            'course_id' => $courseIds['Bachelor of Secondary Education'],
        ],
        
        // Computer Studies majors
        [
            'name' => 'Artificial Intelligence',
            'course_id' => $courseIds['Bachelor of Science in Computer Science'],
        ],
        [
            'name' => 'Data Science',
            'course_id' => $courseIds['Bachelor of Science in Computer Science'],
        ],
        [
            'name' => 'Network Administration',
            'course_id' => $courseIds['Bachelor of Science in Information Technology'],
        ],
        [
            'name' => 'Web Development',
            'course_id' => $courseIds['Bachelor of Science in Information Technology'],
        ],
        [
            'name' => 'Enterprise Systems',
            'course_id' => $courseIds['Bachelor of Science in Information Systems'],
        ],
        [
            'name' => 'Business Intelligence',
            'course_id' => $courseIds['Bachelor of Science in Information Systems'],
        ]
    ];

    echo "\nAdding majors...\n";
    foreach ($majors as $major) {
        $exists = \DB::table('majors')
            ->where('name', $major['name'])
            ->where('course_id', $major['course_id'])
            ->exists();
        
        if (!$exists) {
            $id = \DB::table('majors')->insertGetId([
                'name' => $major['name'],
                'course_id' => $major['course_id'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "  - Added {$major['name']} with ID: $id\n";
        } else {
            echo "  - {$major['name']} already exists\n";
        }
    }

    echo "\n=== Regular Admin and Academic Data Creation Complete ===\n";
    echo "\nCredentials for the regular admin:\n";
    echo "Username: admin\n";
    echo "Email: admin@example.com\n";
    echo "Password: admin123\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}
