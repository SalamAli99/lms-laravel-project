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
       Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // External video link (YouTube, Vimeo, etc.)
            $table->string('video_url')->nullable();

            // Lesson order inside the course
            $table->unsignedInteger('order')->default(1);

            // Draft / Published
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            // Optional: prevent duplicate order in same course
            $table->unique(['course_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('lessons');
    }
};
