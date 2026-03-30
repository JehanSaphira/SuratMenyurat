<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $indexes = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_division_id_unique'");
        if (!empty($indexes)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['division_id']);
            });
        }

        $indexes = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_division_id_index'");
        if (empty($indexes)) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['division_id']);
            });
        }
    }

    public function down(): void
    {
        $indexes = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_division_id_index'");
        if (!empty($indexes)) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['division_id']);
            });
        }

        $indexes = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_division_id_unique'");
        if (empty($indexes)) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique(['division_id']);
            });
        }
    }
};
