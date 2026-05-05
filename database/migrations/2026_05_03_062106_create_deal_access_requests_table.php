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
    Schema::create('deal_access_requests', function (Blueprint $table) {
        $table->id();

        $table->foreignId('nda_acceptance_id')->nullable()->constrained('nda_acceptances')->nullOnDelete();
        $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();

        $table->text('rejection_reason')->nullable();

        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('deal_access_requests');
}
};
