<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('letter_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters');
            $table->foreignId('division_id')->constrained('divisions');
            $table->enum('status', ['pending', 'read', 'approved', 'rejected', 'replied'])->default('pending');
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->unique(['letter_id', 'division_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_targets');
    }
};
