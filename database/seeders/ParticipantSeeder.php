<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define organizations and their participant counts
        $organizations = [
            'Department of Education (DepEd)' => 80,
            'Department of Health (DOH)' => 75,
            'Philippine National Police (PNP)' => 70,
            'Bureau of Fire Protection (BFP)' => 60,
            'Local Government Unit - Barangay' => 55,
            'Department of Social Welfare and Development (DSWD)' => 50,
            'Philippine Red Cross' => 45,
            'Coast Guard Auxiliary' => 35,
            'Volunteer Fire Brigade' => 30,
        ];

        // Positions for each organization type
        $positions = [
            'Department of Education (DepEd)' => [
                'School Principal', 'Teacher III', 'Teacher II', 'Teacher I', 
                'School Nurse', 'Guidance Counselor', 'Administrative Officer',
                'Security Guard', 'Maintenance Staff'
            ],
            'Department of Health (DOH)' => [
                'Medical Officer', 'Public Health Nurse', 'Medical Technologist',
                'Pharmacist', 'Health Inspector', 'Administrative Assistant',
                'Rural Health Midwife', 'Sanitary Inspector'
            ],
            'Philippine National Police (PNP)' => [
                'Police Officer III', 'Police Officer II', 'Police Officer I',
                'Senior Police Officer', 'Police Corporal', 'Police Sergeant',
                'Police Lieutenant', 'Desk Officer'
            ],
            'Bureau of Fire Protection (BFP)' => [
                'Fire Officer III', 'Fire Officer II', 'Fire Officer I',
                'Senior Fire Officer', 'Fire Inspector', 'Emergency Responder',
                'Fire Truck Operator', 'Communications Officer'
            ],
            'Local Government Unit - Barangay' => [
                'Barangay Captain', 'Barangay Councilor', 'Barangay Secretary',
                'Barangay Treasurer', 'SK Chairman', 'Barangay Health Worker',
                'Barangay Tanod', 'Administrative Aide'
            ],
            'Department of Social Welfare and Development (DSWD)' => [
                'Social Worker III', 'Social Worker II', 'Social Worker I',
                'Program Coordinator', 'Case Manager', 'Community Organizer',
                'Administrative Officer', 'Field Supervisor'
            ],
            'Philippine Red Cross' => [
                'Volunteer Coordinator', 'Emergency Response Team Leader',
                'First Aid Instructor', 'Blood Services Coordinator',
                'Community Volunteer', 'Disaster Response Volunteer',
                'Youth Volunteer', 'Safety Services Volunteer'
            ],
            'Coast Guard Auxiliary' => [
                'Auxiliary Commander', 'Patrol Officer', 'Communications Officer',
                'Search and Rescue Volunteer', 'Marine Safety Inspector',
                'Port Security Officer', 'Navigation Assistant'
            ],
            'Volunteer Fire Brigade' => [
                'Brigade Chief', 'Assistant Chief', 'Fire Volunteer',
                'Emergency Medical Technician', 'Equipment Operator',
                'Communications Volunteer', 'Training Officer'
            ]
        ];

        // Common Filipino first names
        $firstNames = [
            'Maria', 'Jose', 'Antonio', 'Juan', 'Ana', 'Francisco', 'Rosa', 'Manuel',
            'Carmen', 'Jesus', 'Luz', 'Ricardo', 'Remedios', 'Mario', 'Josefa',
            'Luis', 'Esperanza', 'Pedro', 'Cristina', 'Roberto', 'Gloria', 'Fernando',
            'Teresa', 'Carlos', 'Corazon', 'Eduardo', 'Lourdes', 'Ramon', 'Victoria',
            'Miguel', 'Leticia', 'Alfredo', 'Mercedes', 'Arturo', 'Milagros', 'Reynaldo',
            'Elena', 'Ernesto', 'Dolores', 'Rodolfo', 'Rosario', 'Benjamin', 'Erlinda',
            'Daniel', 'Norma', 'Enrique', 'Adelaida', 'Armando', 'Angelita', 'Rogelio'
        ];

        // Common Filipino middle names
        $middleNames = [
            'Cruz', 'Santos', 'Reyes', 'Ramos', 'Mendoza', 'Garcia', 'Torres', 'Gonzales',
            'Lopez', 'Flores', 'Rivera', 'Martinez', 'Hernandez', 'Perez', 'Morales',
            'Aquino', 'Villanueva', 'Castro', 'Santiago', 'Manalo', 'Francisco', 'Dela Cruz',
            'Bautista', 'Pascual', 'Aguilar', 'Valdez', 'Soriano', 'Mercado', 'Castillo',
            'Jimenez', null, null, null // Some participants won't have middle names
        ];

        // Common Filipino last names
        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia', 'Gonzales', 'Torres', 'Flores',
            'Rivera', 'Ramos', 'Mendoza', 'Lopez', 'Martinez', 'Hernandez', 'Perez', 'Morales',
            'Francisco', 'Castro', 'Santiago', 'Aquino', 'Villanueva', 'Manalo', 'Pascual',
            'Aguilar', 'Valdez', 'Soriano', 'Mercado', 'Castillo', 'Jimenez', 'Dela Cruz',
            'Velasco', 'Fernandez', 'Navarro', 'Cabrera', 'Alvarez', 'Salazar', 'Rosario',
            'Gutierrez', 'Ortega', 'Romero', 'Suarez', 'Vargas', 'Herrera', 'Guerrero',
            'Medina', 'Rojas', 'Campos', 'Contreras', 'Silva', 'Luna', 'Vega'
        ];

        // Vulnerable groups
        $vulnerableGroups = [
            'Persons with Disabilities (PWDs)',
            'Senior Citizens', 
            'Pregnant'
        ];

        foreach ($organizations as $org => $count) {
            for ($i = 0; $i < $count; $i++) {
                // Generate random vulnerable groups (20% chance of having any)
                $participantVulnerableGroups = [];
                if (rand(1, 5) == 1) { // 20% chance
                    $numGroups = rand(1, 2); // 1-2 vulnerable groups max
                    $selectedGroups = array_rand(array_flip($vulnerableGroups), $numGroups);
                    $participantVulnerableGroups = is_array($selectedGroups) ? $selectedGroups : [$selectedGroups];
                }

                Participant::create([
                    'id_no' => rand(10000, 99999) . '-' . rand(1000, 9999),
                    'first_name' => $firstNames[array_rand($firstNames)],
                    'middle_name' => $middleNames[array_rand($middleNames)],
                    'last_name' => $lastNames[array_rand($lastNames)],
                    'agency_organization' => $org,
                    'position_designation' => $positions[$org][array_rand($positions[$org])],
                    'sex' => rand(0, 1) ? 'male' : 'female',
                    'vulnerable_groups' => empty($participantVulnerableGroups) ? [] : $participantVulnerableGroups,
                ]);
            }
        }

        $this->command->info('Created 500 participants across ' . count($organizations) . ' organizations.');
    }
}
