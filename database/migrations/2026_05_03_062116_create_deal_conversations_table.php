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
    Schema::create('deal_conversations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();

        $table->enum('status', ['open', 'closed'])->default('open');

        $table->timestamps();

        $table->unique(['buyer_id', 'seller_id', 'deal_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('deal_conversations');
}
};
