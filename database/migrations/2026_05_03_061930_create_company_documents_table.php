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
    Schema::create('company_documents', function (Blueprint $table) {
        $table->id();

        $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
        $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();

        $table->enum('document_type', [
            'business_license',
            'tax_document',
            'financial_statement',
            'ownership_document',
            'other'
        ])->default('other');

        $table->string('file_name');
        $table->string('file_path', 500);

        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('note')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('company_documents');
}
};
