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
        Schema::create('maintains', function (Blueprint $table) {
             $table->id();
             $table->string('password');
             $table->string('role');
             $table->string('image')->nullable();
             $table->string('name')->nullable();
             $table->string('maintain_username')->nullable();
             $table->string('login_device')->nullable();
             $table->string('email')->unique();
             $table->string('phone')->unique();
             $table->string('status')->nullable();
             $table->string('login_code')->nullable();
             $table->string('login_time')->nullable();
             $table->string('forget_code')->nullable();
             $table->string('forget_time')->nullable();
             $table->string('comments')->nullable();
             $table->string('view_access')->nullable();
             $table->string('delete_access')->nullable();
             $table->string('edit_access')->nullable();
             $table->string('access_number')->nullable();
             $table->string('update_data')->nullable();
             $table->string('others')->nullable();
             $table->string('application')->nullable();
             $table->string('resign')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintains');
    }
};
