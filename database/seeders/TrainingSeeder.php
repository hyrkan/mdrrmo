<?php

namespace Database\Seeders;

use App\Models\Training;
use App\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Training types and organizations
        $trainingTypes = [
            'Disaster Risk Reduction and Management',
            'First Aid and Basic Life Support',
            'Community Emergency Response Team (CERT)',
            'Fire Safety and Prevention',
            'Earthquake Preparedness and Response',
            'Flood Response and Evacuation',
            'Incident Command System (ICS)',
            'Search and Rescue Operations',
            'Emergency Medical Response',
            'Psychological First Aid',
            'Community-Based Disaster Risk Management',
            'Climate Change Adaptation',
            'Business Continuity Planning',
            'School Safety and Emergency Preparedness',
            'Hospital Emergency Response',
            'Multi-Hazard Risk Assessment',
            'Early Warning Systems',
            'Post-Disaster Recovery Planning',
            'Humanitarian Response Coordination',
            'Emergency Communications'
        ];

        $organizations = [
            'Department of Education (DepEd)',
            'Department of Health (DOH)',
            'Philippine National Police (PNP)',
            'Bureau of Fire Protection (BFP)',
            'Local Government Unit - Barangay',
            'Department of Social Welfare and Development (DSWD)',
            'Philippine Red Cross',
            'Coast Guard Auxiliary',
            'Volunteer Fire Brigade',
            'Office of Civil Defense (OCD)',
            'Department of Public Works and Highways (DPWH)',
            'Philippine Coast Guard',
            'Armed Forces of the Philippines (AFP)',
            'Department of Environment and Natural Resources (DENR)',
            'Metropolitan Manila Development Authority (MMDA)',
            'Philippine Atmospheric, Geophysical and Astronomical Services Administration (PAGASA)'
        ];

        $venues = [
            'Quezon City Hall Conference Room',
            'Manila City Emergency Operations Center',
            'Makati City Disaster Risk Reduction and Management Office',
            'Pasig City Multi-Purpose Hall',
            'Caloocan City Community Center',
            'Marikina City Sports Center',
            'San Juan City Hall Auditorium',
            'Muntinlupa City Training Center',
            'Taguig City Emergency Response Center',
            'Parañaque City Convention Center',
            'Las Piñas City Civic Center',
            'Valenzuela City Training Facility',
            'Malabon City Community Hall',
            'Navotas City Emergency Center',
            'Pateros Municipal Hall',
            'Pasay City Emergency Operations Center',
            'Philippine Red Cross National Headquarters',
            'BFP Training Center',
            'PNP Training Academy',
            'DOH Regional Office Conference Hall'
        ];

        $facilitators = [
            'Dr. Maria Santos, MD',
            'Engr. Juan Dela Cruz',
            'Prof. Ana Reyes, PhD',
            'Fire Chief Ricardo Martinez',
            'Police Colonel Eduardo Garcia',
            'Dr. Carmen Torres, MD',
            'Engr. Roberto Gonzales',
            'Ms. Gloria Hernandez, RN',
            'Captain Fernando Lopez',
            'Dr. Luis Morales, MD',
            'Architect Rosa Aquino',
            'Engineer Miguel Castro',
            'Dr. Elena Santiago, MD',
            'Commander Benjamin Rivera',
            'Prof. Corazon Villanueva, PhD',
            'Fire Superintendent Carlos Ramos',
            'Dr. Victoria Mendoza, MD',
            'Engineer Daniel Flores',
            'Ms. Luz Pascual, RN',
            'Captain Arturo Bautista'
        ];

        $instructors = [
            'Emergency Response Training Institute',
            'Philippine Disaster Risk Reduction Foundation',
            'National Emergency Management Institute',
            'Asian Disaster Preparedness Center',
            'Philippine Red Cross Training Center',
            'Bureau of Fire Protection Training Academy',
            'Philippine National Police Training Service',
            'Department of Health Emergency Medical Services',
            'Office of Civil Defense Training Center',
            'International Association of Fire Chiefs',
            'Disaster Risk Reduction Network Philippines',
            'Community Emergency Response Alliance',
            'Philippine Association of Emergency Managers',
            'Southeast Asian Disaster Prevention Research Initiative',
            'United Nations Office for Disaster Risk Reduction'
        ];

        $requestingParties = [
            'Metro Manila Development Authority',
            'Quezon City Government',
            'Manila City Government',
            'Makati City Government',
            'Pasig City Government',
            'Taguig City Government',
            'Marikina City Government',
            'Philippine Red Cross - Metro Manila',
            'Department of Education - NCR',
            'Department of Health - NCR',
            'Office of Civil Defense - NCR',
            'Bureau of Fire Protection - NCR',
            'Philippine National Police - NCR',
            'Caloocan City Government',
            'Muntinlupa City Government'
        ];

        $allParticipants = Participant::all();
        
        if ($allParticipants->count() < 5000) {
            $this->command->warn('Not enough participants in database. Creating more participants first...');
            // Create additional participants to ensure we have enough
            $this->createAdditionalParticipants(5000 - $allParticipants->count());
            $allParticipants = Participant::all();
        }

        $this->command->info('Creating 100 trainings with 50+ participants each...');
        $bar = $this->command->getOutput()->createProgressBar(100);
        $bar->start();

        // Create 100 trainings
        for ($i = 1; $i <= 100; $i++) {
            // Generate random training dates (past 2 years to future 6 months)
            $startDate = Carbon::now()->subMonths(24)->addDays(rand(0, 730));
            $trainingDuration = rand(1, 5); // 1-5 days
            
            $dates = [];
            for ($d = 0; $d < $trainingDuration; $d++) {
                $dates[] = $startDate->copy()->addDays($d)->format('Y-m-d');
            }

            $trainingName = $trainingTypes[array_rand($trainingTypes)];
            
            // Add some variety to training names
            $suffixes = [
                '',
                ' - Basic Level',
                ' - Advanced Training',
                ' - Refresher Course',
                ' - Certification Program',
                ' - Workshop',
                ' - Seminar',
                ' - Intensive Course'
            ];
            
            $trainingName .= $suffixes[array_rand($suffixes)];

            // Create training
            $training = Training::create([
                'name' => $trainingName,
                'dates' => $dates,
                'organized_by' => $organizations[array_rand($organizations)],
                'requesting_party' => $requestingParties[array_rand($requestingParties)],
                'venue' => $venues[array_rand($venues)],
                'course_facilitator' => $facilitators[array_rand($facilitators)],
                'instructor' => $instructors[array_rand($instructors)],
                'created_at' => $startDate->subDays(rand(7, 30)), // Created 7-30 days before training
                'updated_at' => $startDate->subDays(rand(1, 7))   // Updated 1-7 days before training
            ]);

            // Randomly assign 50-100 participants to each training
            $participantCount = rand(50, 100);
            $selectedParticipants = $allParticipants->random($participantCount);

            $pivotData = [];
            foreach ($selectedParticipants as $participant) {
                // Random completion status based on training date
                $completionStatus = 'enrolled';
                $completedAt = null;
                $certificate = false;
                $certificateSerial = null;
                $issuedBy = null;
                $certificateIssuedAt = null;

                // If training is in the past, assign realistic completion statuses
                if ($startDate->isPast()) {
                    $statusChance = rand(1, 100);
                    if ($statusChance <= 80) { // 80% completion rate
                        $completionStatus = 'completed';
                        $completedAt = $startDate->copy()->addDays($trainingDuration - 1);
                        
                        // 90% of completed participants get certificates
                        if (rand(1, 100) <= 90) {
                            $certificate = true;
                            $certificateSerial = 'CERT-' . $training->id . '-' . str_pad($participant->id, 4, '0', STR_PAD_LEFT);
                            $issuedBy = $training->organized_by;
                            $certificateIssuedAt = $completedAt->copy()->addDays(rand(1, 14));
                        }
                    } elseif ($statusChance <= 90) { // 10% did not complete
                        $completionStatus = 'did_not_complete';
                    }
                    // 10% remain enrolled (no show or ongoing)
                }

                $pivotData[] = [
                    'training_id' => $training->id,
                    'participant_id' => $participant->id,
                    'completion_status' => $completionStatus,
                    'completed_at' => $completedAt,
                    'certificate' => $certificate,
                    'certificate_serial' => $certificateSerial,
                    'issued_by' => $issuedBy,
                    'certificate_issued_at' => $certificateIssuedAt,
                    'created_at' => $training->created_at,
                    'updated_at' => $training->updated_at
                ];
            }

            // Bulk insert pivot data for better performance
            DB::table('training_participant')->insert($pivotData);

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Successfully created 100 trainings with participant assignments!');
        
        // Show statistics
        $totalParticipantAssignments = DB::table('training_participant')->count();
        $completedCount = DB::table('training_participant')->where('completion_status', 'completed')->count();
        $certificateCount = DB::table('training_participant')->where('certificate', true)->count();
        
        $this->command->info("Statistics:");
        $this->command->info("- Total participant assignments: {$totalParticipantAssignments}");
        $this->command->info("- Completed trainings: {$completedCount}");
        $this->command->info("- Certificates issued: {$certificateCount}");
    }

    private function createAdditionalParticipants($count)
    {
        $organizations = [
            'Department of Education (DepEd)',
            'Department of Health (DOH)',
            'Philippine National Police (PNP)',
            'Bureau of Fire Protection (BFP)',
            'Local Government Unit - Barangay',
            'Department of Social Welfare and Development (DSWD)',
            'Philippine Red Cross',
            'Coast Guard Auxiliary',
            'Volunteer Fire Brigade',
            'Office of Civil Defense (OCD)',
            'Department of Public Works and Highways (DPWH)',
            'Philippine Coast Guard',
            'Armed Forces of the Philippines (AFP)',
            'Department of Environment and Natural Resources (DENR)',
            'Metropolitan Manila Development Authority (MMDA)',
            'Philippine Atmospheric, Geophysical and Astronomical Services Administration (PAGASA)'
        ];

        $positions = [
            'Administrative Officer', 'Program Coordinator', 'Field Officer', 'Senior Officer',
            'Junior Officer', 'Specialist', 'Supervisor', 'Manager', 'Assistant Manager',
            'Team Leader', 'Project Manager', 'Technical Officer', 'Operations Officer',
            'Emergency Responder', 'Volunteer', 'Trainer', 'Coordinator', 'Inspector'
        ];

        $firstNames = [
            'Maria', 'Jose', 'Antonio', 'Juan', 'Ana', 'Francisco', 'Rosa', 'Manuel',
            'Carmen', 'Jesus', 'Luz', 'Ricardo', 'Remedios', 'Mario', 'Josefa',
            'Luis', 'Esperanza', 'Pedro', 'Cristina', 'Roberto', 'Gloria', 'Fernando',
            'Teresa', 'Carlos', 'Corazon', 'Eduardo', 'Lourdes', 'Ramon', 'Victoria'
        ];

        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia', 'Gonzales', 'Torres', 'Flores',
            'Rivera', 'Ramos', 'Mendoza', 'Lopez', 'Martinez', 'Hernandez', 'Perez', 'Morales'
        ];

        $participants = [];
        for ($i = 0; $i < $count; $i++) {
            $participants[] = [
                'id_no' => rand(10000, 99999) . '-' . rand(1000, 9999),
                'first_name' => $firstNames[array_rand($firstNames)],
                'middle_name' => (rand(1, 3) == 1) ? null : $lastNames[array_rand($lastNames)],
                'last_name' => $lastNames[array_rand($lastNames)],
                'agency_organization' => $organizations[array_rand($organizations)],
                'position_designation' => $positions[array_rand($positions)],
                'sex' => rand(0, 1) ? 'male' : 'female',
                'vulnerable_groups' => json_encode([]), // Store as JSON string
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert in chunks for better performance
        foreach (array_chunk($participants, 100) as $chunk) {
            DB::table('participants')->insert($chunk);
        }

        $this->command->info("Created {$count} additional participants.");
    }
}
