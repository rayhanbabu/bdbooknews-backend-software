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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dept_id');
            $table->foreign('dept_id')->references('id')->on('depts');

            $table->string('category_name_id'); // Foreign key column
            $table->foreign('category_name_id')->references('category_name')->on('categories');
          
            $table->string('subcategory_name_id'); // Foreign key column
            $table->foreign('subcategory_name_id')->references('subcategory_name')->on('subcategories');

            $table->text('title');
           
            $table->longText('desc');
            $table->string('image');

            $table->longText('desc1')->nullable();
            $table->string('image1')->nullable();

            $table->longText('desc2')->nullable();
            $table->string('image2')->nullable();
 
            $table->integer('division_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('upazila_id')->nullable();
            $table->integer('highlight_serial')->default(0);
            $table->integer('latest_serial')->default(0);
            $table->integer('geater_serial')->default(0);

            $table->string('link')->nullable();
            $table->string('author')->nullable();
            $table->string('reporter_name')->nullable();
           
            $table->string('news_status')->default(1);
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
        Schema::dropIfExists('news');
    }
};
