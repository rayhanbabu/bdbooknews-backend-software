<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id');
            $table->foreign('dept_id')->references('id')->on('depts');
            $table->string('teacher_name');
            $table->string('role');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('nickname')->nullable();
            $table->string('designation')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('academic_qualification')->nullable();
            $table->string('password');
            $table->string('bank_details')->nullable();
            $table->string('forget_password')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('teacher_status')->nullable();
            $table->string('subject_access')->nullable();
            $table->string('login_code')->nullable();
            $table->string('login_time')->nullable();
            $table->string('forget_code')->nullable();
            $table->string('forget_time')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
