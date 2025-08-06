<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bio')->nullable();
            $table->string('location')->nullable(); // Already included for location
            $table->string('batch')->nullable();
            $table->string('course')->nullable();
            $table->string('current_status')->nullable();
            $table->json('skills')->nullable();
            $table->json('socials')->nullable(); // For social media links
            $table->string('phone')->nullable(); // For phone number
            $table->string('photo')->nullable(); // For profile picture path or URL
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}