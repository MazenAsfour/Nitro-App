<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::create([
            "firstname" => "Mazen",
            'lastname' => "Asfour",
            'middlename' => "Emad",
            'username' => "Mazen Asfour",
            'type' => "user",
            'prefixname' => "Mr",
            'email' =>"Mazenasfour6@gmail.com",
            "password"=>Hash::make("mazen@123"),
            'photo' => 'http://127.0.0.1:8000/images/mazen.jpeg'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
