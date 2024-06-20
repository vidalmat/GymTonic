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
        Schema::create('member_document', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->nullable()->constrained('documents');
            $table->foreignUuid('member_id')->nullable()->constrained('members');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_document');
    }
};
