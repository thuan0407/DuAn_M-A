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
    Schema::create('deals', function (Blueprint $table) {
        $table->id();

        $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

        $table->string('title');

        $table->enum('deal_type', ['acquisition', 'share_sale', 'fundraising']);

        $table->string('industry')->nullable();
        $table->string('location')->nullable();

        $table->text('short_description')->nullable();
        $table->text('full_description')->nullable();

        $table->decimal('target_amount', 18, 2)->nullable();
        $table->decimal('valuation', 18, 2)->nullable();
        $table->decimal('equity_offered_percent', 5, 2)->nullable();
        $table->decimal('min_ticket', 18, 2)->nullable();

        $table->boolean('allow_multiple_investors')->default(false);

        $table->string('currency', 10)->default('VND');

        $table->enum('status', [
            'pending_review',
            'active',
            'in_transaction',
            'rejected',
            'closed',
            'hidden',
        ])->default('pending_review');
        
        $table->decimal('committed_amount', 18, 2)->default(0);
        $table->decimal('confirmed_amount', 18, 2)->default(0);

        $table->text('rejection_reason')->nullable();

        $table->timestamp('published_at')->nullable();
        $table->timestamp('closed_at')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('deals');
}
};
