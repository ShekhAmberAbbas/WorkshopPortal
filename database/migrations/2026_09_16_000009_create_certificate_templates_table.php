<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 191)->unique();
            $table->string('file_path', 500);
            $table->json('configuration');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->index(['status', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
