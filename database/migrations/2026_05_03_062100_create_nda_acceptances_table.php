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
    Schema::create('nda_acceptances', function (Blueprint $table) {
        $table->id();

        $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
        $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();

        $table->string('nda_version', 50)->default('v1');
        $table->timestamp('accepted_at')->nullable();
        $table->string('accepted_ip', 100)->nullable();

        $table->enum('status', ['signed', 'revoked'])->default('signed');

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('nda_acceptances');
}
};
