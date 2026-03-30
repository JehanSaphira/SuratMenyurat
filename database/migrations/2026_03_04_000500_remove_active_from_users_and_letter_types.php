<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }

        if (Schema::hasTable('letter_types') && Schema::hasColumn('letter_types', 'active')) {
            Schema::table('letter_types', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('active')->default(true);
            });
        }

        if (Schema::hasTable('letter_types') && !Schema::hasColumn('letter_types', 'active')) {
            Schema::table('letter_types', function (Blueprint $table) {
                $table->boolean('active')->default(true);
            });
        }
    }
};
