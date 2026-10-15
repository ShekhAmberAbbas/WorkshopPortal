<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('participant_id')->unique()->constrained('participants')->cascadeOnDelete();
            $table->foreignId('workshop_id')->constrained('workshops')->cascadeOnDelete();
            $table->foreignId('certificate_template_id')->nullable()->constrained('certificate_templates')->nullOnDelete();
            $table->string('certificate_id', 30)->unique();
            $table->string('verification_token', 64)->unique();
            $table->string('file_path', 500);
            $table->date('issue_date');
            $table->enum('status', ['pending', 'generated', 'revoked'])->default('pending');
            $table->timestamps();
            $table->index(['workshop_id', 'status']);
            $table->index('issue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
