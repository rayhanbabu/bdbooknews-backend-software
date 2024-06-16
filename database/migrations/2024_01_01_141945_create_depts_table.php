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
        Schema::create('depts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('university_id');
            $table->foreign('university_id')->references('id')->on('univers');
            $table->string('dept_name');
            $table->string('dept_address')->nullable();
            $table->string('dept_code')->nullable();
            $table->string('faculty')->nullable();
            $table->string('established_date')->nullable();
            $table->string('created_by')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depts');
    }
};
