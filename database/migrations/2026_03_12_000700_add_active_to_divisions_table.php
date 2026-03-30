<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('divisions')) {
            return;
        }

        Schema::table('divisions', function (Blueprint $table) {
            if (!Schema::hasColumn('divisions', 'active')) {
                $table->boolean('active')->default(true)->after('number_format');
            }
        });
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            if (Schema::hasColumn('divisions', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
