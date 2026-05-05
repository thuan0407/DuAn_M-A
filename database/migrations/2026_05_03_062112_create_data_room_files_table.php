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
    Schema::create('data_room_files', function (Blueprint $table) {
        $table->id();

        $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
        $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();

        $table->enum('document_type', [
            'financial_statement',
            'legal_document',
            'tax_document',
            'contract',
            'business_plan',
            'other'
        ])->default('other');

        $table->string('file_name');
        $table->string('file_path', 500);

        $table->boolean('allow_download')->default(false);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('data_room_files');
}
};
