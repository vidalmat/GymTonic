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
        if (!Schema::hasTable('mail_recipient')) {
            Schema::create('mail_recipient', function (Blueprint $table) {
                $table->foreignUuid('mail_id')->constrained('mails');
                $table->foreignUuid('member_id')->constrained('members');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_recipient');
    }
};

