<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('ustatus', [1, 2, 3])->default(1);
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('username');
            $table->string('password');
            $table->string('role');
            $table->string('gender');
            $table->integer('posted_by');
            $table->string('avatar');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'ustatus' => 1,
            'fname' => 'Admin',
            'mname' => '',
            'lname' => 'User',
            'username' => 'administrator',
            'password' => Hash::make('password123'),
            'role' => 'Administrator',
            'gender' => 'Male',
            'posted_by' => 1,
            'avatar' => '',
            'remember_token' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
