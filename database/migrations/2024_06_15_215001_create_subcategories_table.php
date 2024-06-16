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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id');
            $table->foreign('dept_id')->references('id')->on('depts');

            $table->string('category_name_id'); // Foreign key column
            $table->foreign('category_name_id')->references('category_name')->on('categories');
            
            
            $table->string('image')->nullable();
            $table->string('subcategory_name')->unique();

            $table->string('sub_name')->unique();
            $table->string('subcategory_status')->default(1);
            
            $table->integer('serial')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
