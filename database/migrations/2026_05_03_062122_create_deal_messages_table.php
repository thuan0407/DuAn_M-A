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
    Schema::create('deal_messages', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('conversation_id')->constrained('deal_conversations')->cascadeOnDelete();

        $table->text('message')->nullable();
        $table->string('file_path', 500)->nullable();

        $table->timestamp('read_at')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('deal_messages');
}
};
