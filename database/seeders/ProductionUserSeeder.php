<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionUserSeeder extends Seeder
{
    /**
     * Create the default admin user for production.
     */
    public function run(): void
    {
        $this->command->info('Creating admin user for production...');

        // Check if admin already exists
        if (User::where('email', 'admin@skansaba.sch.id')->exists()) {
            $this->command->warn('Admin user already exists, skipping...');
            return;
        }

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@skansaba.sch.id',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $admin->assignRole('admin');

        $this->command->info('✓ Admin user created.');
        $this->command->warn('');
        $this->command->warn('  IMPORTANT: Change the default password immediately!');
        $this->command->warn('  Email: admin@skansaba.sch.id');
        $this->command->warn('  Password: password');
        $this->command->warn('');
    }
}
