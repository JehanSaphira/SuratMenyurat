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

        $columns = [
            'counter',
            'counter_reset',
            'counter_month',
            'counter_year',
            'active',
        ];

        $existing = array_filter($columns, fn ($column) => Schema::hasColumn('divisions', $column));
        if (empty($existing)) {
            return;
        }

        Schema::table('divisions', function (Blueprint $table) use ($existing) {
            $table->dropColumn($existing);
        });
    }

    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            if (!Schema::hasColumn('divisions', 'counter')) {
                $table->unsignedInteger('counter')->default(1);
            }
            if (!Schema::hasColumn('divisions', 'counter_reset')) {
                $table->enum('counter_reset', ['monthly', 'yearly', 'never'])->default('yearly');
            }
            if (!Schema::hasColumn('divisions', 'counter_month')) {
                $table->unsignedInteger('counter_month')->nullable();
            }
            if (!Schema::hasColumn('divisions', 'counter_year')) {
                $table->unsignedInteger('counter_year')->nullable();
            }
            if (!Schema::hasColumn('divisions', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }
};
