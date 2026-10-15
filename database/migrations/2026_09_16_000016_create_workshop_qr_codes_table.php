<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_id')->unique()->constrained('workshops')->cascadeOnDelete();
            $table->string('file_path', 500);
            $table->string('registration_url', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_qr_codes');
    }
};
