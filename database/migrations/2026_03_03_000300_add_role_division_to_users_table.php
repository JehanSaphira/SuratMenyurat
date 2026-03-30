<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'division'])->default('division');
            $table->foreignId('division_id')->nullable()->constrained('divisions');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['division_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['division_id']);
            $table->dropConstrainedForeignId('division_id');
            $table->dropColumn(['role']);
        });
    }
};
