<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('document_type', ['cccd', 'passport', 'driver_license'])->default('cccd');
            $table->string('document_number', 100);
            $table->string('document_front', 500)->nullable();
            $table->string('document_back', 500)->nullable();
            $table->string('selfie_image', 500)->nullable();

            $table->string('company', 100)->nullable();
            $table->string('position', 50)->nullable();
            $table->string('experience', 500)->nullable();
            $table->string('goal', 500)->nullable();
            $table->string('phone', 15)->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};