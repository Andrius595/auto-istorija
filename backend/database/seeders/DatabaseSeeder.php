<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Record;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $systemAdmin = Role::create(['name' => PermissionsConfig::SYSTEM_ADMIN]);
        $client = Role::create(['name' => PermissionsConfig::CLIENT_ROLE]);
        $serviceEmployee = Role::create(['name' => PermissionsConfig::SERVICE_EMPLOYEE_ROLE]);
        $serviceAdmin = Role::create(['name' => PermissionsConfig::SERVICE_ADMIN_ROLE]);

        $permissions = PermissionsConfig::SYSTEM_ADMIN_PERMISSIONS;
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $systemAdmin->syncPermissions($permissions);

        $permissions = PermissionsConfig::CLIENT_PERMISSIONS;
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $client->syncPermissions($permissions);

        $permissions = PermissionsConfig::SERVICE_EMPLOYEE_PERMISSIONS;
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $serviceEmployee->syncPermissions($permissions);


        $service = Service::create([
            'title' => 'Test Service',
        ]);


        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'client@example.com',
        ]);

        $user->assignRole($client);

        $seUser = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Service Employee',
            'email' => 'employee@example.com',
            'service_id' => $service->id,
        ]);

        $seUser->assignRole($serviceEmployee);

        $saUser = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Service Admin',
            'email' => 'admin@example.com',
        ]);

        $saUser->assignRole($serviceAdmin);

        $systemAdminUser = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'System Admin',
            'email' => 'system@example.com',
        ]);

        $systemAdminUser->assignRole($systemAdmin);

        $car = Car::create([
            'make' => 'Volvo',
            'model' => 'V60',
            'vin' => '123',
            'owner_id' => $user->id,
            'year_of_manufacture' => 2011,
        ]);

        $appointment = Appointment::create([
            'service_id' => $service->id,
            'car_id' => $car->id,
            'confirmed_at' => now(),
            'completed_at' => now(),
            'created_at' => now()->subMonth(),
        ]);

        Record::create([
            'appointment_id' => $appointment->id,
            'current_mileage' => 190000,
            'mileage_type' => Car::MILEAGE_TYPE_KILOMETERS,
            'description' => 'Oil change',
        ]);

        $appointment2 = Appointment::create([
            'service_id' => $service->id,
            'car_id' => $car->id,
            'confirmed_at' => now(),
            'completed_at' => now(),
        ]);
        Record::create([
            'appointment_id' => $appointment2->id,
            'current_mileage' => 200000,
            'mileage_type' => Car::MILEAGE_TYPE_KILOMETERS,
            'description' => 'Flywheel change',
        ]);

        Service::factory(10)->create();
        $users = User::factory(10)->create();
        Car::factory(10)->create();

        foreach ($users as $user) {
            $user->assignRole($client);
        }
    }
}
