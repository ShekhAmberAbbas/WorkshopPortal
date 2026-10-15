<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('access_token', 64)->unique();
            $table->foreignId('workshop_id')->constrained('workshops')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name', 191);
            $table->string('email', 191);
            $table->string('mobile', 20);
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('course', 100);
            $table->string('branch', 150);
            $table->string('year', 50);
            $table->string('college_name', 191);
            $table->string('enrollment_number', 50)->nullable();
            $table->string('enrollment_id', 150)->nullable();
            $table->string('home_state', 100)->nullable();
            $table->string('home_district', 100)->nullable();

            // Fixed, application-level feedback. Optional; no feedback-question tables.
            $table->string('feedback_workshop_rating', 30)->nullable();
            $table->string('feedback_previous_quaere_training', 30)->nullable();
            $table->string('feedback_trainee_interest', 80)->nullable();
            $table->string('feedback_program_interest', 100)->nullable();
            $table->string('feedback_practical_oriented', 30)->nullable();
            $table->string('feedback_industry_relevant', 30)->nullable();
            $table->string('feedback_speaker', 100)->nullable();
            $table->string('feedback_speaker_rating', 30)->nullable();
            $table->string('feedback_speaker_answered_questions', 30)->nullable();
            $table->text('feedback_comments')->nullable();
            $table->timestamp('feedback_submitted_at')->nullable();

            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();

            $table->unique(['workshop_id', 'email'], 'uniq_participants_workshop_email');
            $table->unique(['workshop_id', 'mobile'], 'uniq_participants_workshop_mobile');
            $table->unique(['workshop_id', 'enrollment_id'], 'uniq_workshop_enrollment_id');
            $table->index('email');
            $table->index('mobile');
            $table->index('enrollment_number');
            $table->index('home_state');
            $table->index('home_district');
            $table->index('college_name');
            $table->index(['course_id', 'branch_id']);
            $table->index('registered_at');
            $table->index('feedback_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
