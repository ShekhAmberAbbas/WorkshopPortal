<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_id')->constrained('courses')->restrictOnDelete();
            $table->string('name', 150);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['course_id', 'name']);
            $table->index(['course_id', 'status', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
