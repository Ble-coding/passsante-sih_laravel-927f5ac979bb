<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Receptionist;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultReceptionistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $userTenantId = session('tenant_id', null);

        $departmentID = Department::whereName('Receptionist')->first()->id;

        $receptionistData = [
            'department_id' => $departmentID,
            'first_name' => $faker->unique()->name(),
            'last_name' => $faker->unique()->name(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Hash::make('123456'),
            'designation' => 'patient',
            'status' => 1,
            'email_verified_at' => Carbon::now(),
            'tenant_id' => $userTenantId,
        ];

        $user = User::create($receptionistData);

        $receptionist = Receptionist::create([
            'user_id' => $user->id,
            'tenant_id' => $userTenantId,
        ]);
        $ownerId = $receptionist->id;
        $ownerType = Receptionist::class;

        $user->update(['owner_id' => $ownerId, 'owner_type' => $ownerType]);
        $user->assignRole($departmentID);
    }
}
