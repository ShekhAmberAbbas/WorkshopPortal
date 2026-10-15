<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('workshop_code', 30)->unique();
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->string('college_name', 191);
            $table->text('college_address')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedSmallInteger('duration_days')->default(1);
            $table->string('trainer_name', 150);
            $table->string('trainer_designation', 150)->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive', 'completed'])->default('draft');
            $table->boolean('certificate_enabled')->default(true);
            $table->boolean('auto_generate_certificate')->default(true);
            $table->string('enrollment_prefix', 100)->nullable();
            $table->foreignId('certificate_template_id')->nullable()->constrained('certificate_templates')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'start_date']);
            $table->index('college_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
