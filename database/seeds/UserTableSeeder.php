<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exist = User::where('email', 'admin@quiz.com')->first();
        if ($exist) {
            $exist->delete();
        }

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@quiz.com',
            'password' => bcrypt(env('ADMIN_PASS', 'quiz:com')),
            'intro' => 'Administrator',
            'type' => User::T_ADMIN,
        ]);
    }
}
