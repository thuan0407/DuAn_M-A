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
    Schema::create('companies', function (Blueprint $table) {
        $table->id();

        $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

        $table->string('legal_name');
        $table->string('tax_code', 100)->nullable();
        $table->string('business_registration_number', 100)->nullable();
        $table->text('address')->nullable();
        $table->string('legal_representative_name')->nullable();
        $table->string('industry')->nullable();
        $table->text('description')->nullable();

        $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
        $table->text('rejection_reason')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('companies');
}
};
