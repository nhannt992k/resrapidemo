<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('email', 255)->unique();
            $table->boolean('verified')->false;
            $table->string('address', 255)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->boolean('state')->true;
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
