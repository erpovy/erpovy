<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DemoUserUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'darkovy@gmail.com')->first();
        
        if ($user) {
            $user->update([
                'email' => 'demo@erpovy.com',
                'name' => 'Erpovy Demo Admin'
            ]);
            $this->command->info('User darkovy@gmail.com updated to demo@erpovy.com');
        } else {
            $this->command->warn('User darkovy@gmail.com not found. Checking for demo@erpovy.com...');
            $demoUser = User::where('email', 'demo@erpovy.com')->first();
            if ($demoUser) {
                $this->command->info('demo@erpovy.com already exists.');
            } else {
                $this->command->error('No suitable user found to update.');
            }
        }
    }
}
