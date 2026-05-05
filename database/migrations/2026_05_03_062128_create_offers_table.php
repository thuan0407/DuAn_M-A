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
    Schema::create('offers', function (Blueprint $table) {
        $table->id();

        $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
        $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

        $table->enum('offer_type', ['buyout', 'share_purchase', 'investment']);

        $table->decimal('amount', 18, 2)->nullable();
        $table->decimal('equity_percent', 5, 2)->nullable();
        $table->string('currency', 10)->default('VND');

        $table->text('note')->nullable();

        $table->enum('status', [
            'pending',
            'accepted',
            'rejected',
            'countered',
            'withdrawn',
            'completed'
        ])->default('pending');

        $table->timestamp('buyer_confirmed_at')->nullable();
        $table->timestamp('seller_confirmed_at')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('offers');
}
};
