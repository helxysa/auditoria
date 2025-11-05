<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = [
            ['name' => 'Francimara', 'cargo' => 'Auditor', 'email' => 'francimara@example.com', 'password' => Hash::make('password')],
            ['name' => 'Aymmeé', 'cargo' => 'Auditor', 'email' => 'aymmee@example.com', 'password' => Hash::make('password')],
            ['name' => 'Amanda', 'cargo' => 'Auditor', 'email' => 'amanda@example.com', 'password' => Hash::make('password')],
            ['name' => 'Claudemir', 'cargo' => 'Auditor', 'email' => 'claudemir@example.com', 'password' => Hash::make('password')],
            ['name' => 'Adriano', 'cargo' => 'Auditor', 'email' => 'adriano@example.com', 'password' => Hash::make('password')],
            ['name' => 'Isabelle', 'cargo' => 'Auditor', 'email' => 'isabelle@example.com', 'password' => Hash::make('password')],
            ['name' => 'Diego', 'cargo' => 'Auditor', 'email' => 'diego@example.com', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert(array_merge($user, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $emails = [
            'francimara@example.com',
            'aymmee@example.com',
            'amanda@example.com',
            'claudemir@example.com',
            'adriano@example.com',
            'isabelle@example.com',
            'diego@example.com',
        ];

        DB::table('users')->whereIn('email', $emails)->delete();
    }
};
