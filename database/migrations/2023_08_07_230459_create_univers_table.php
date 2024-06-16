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
        Schema::create('univers', function (Blueprint $table) {
            $table->id();
            $table->string('university');
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('university_code')->nullable();
            $table->string('university_established_date')->nullable();
            $table->string('text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('univers');
    }
};
