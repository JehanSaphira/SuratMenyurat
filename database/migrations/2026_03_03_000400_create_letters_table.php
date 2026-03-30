<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions');
            $table->foreignId('letter_type_id')->constrained('letter_types');
            $table->foreignId('parent_id')->nullable()->constrained('letters');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->string('number')->unique();
            $table->string('subject');
            $table->longText('body');
            $table->text('cc')->nullable();
            $table->json('data')->nullable();
            $table->json('attachments')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['sent', 'completed'])->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
