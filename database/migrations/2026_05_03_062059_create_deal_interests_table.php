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
    Schema::create('deal_interests', function (Blueprint $table) {
        $table->id();

        $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
        $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();

        $table->enum('type', ['saved', 'interested']);

        $table->timestamps();

        $table->unique(['deal_id', 'buyer_id', 'type']);
    });
}

public function down(): void
{
    Schema::dropIfExists('deal_interests');
}
};
