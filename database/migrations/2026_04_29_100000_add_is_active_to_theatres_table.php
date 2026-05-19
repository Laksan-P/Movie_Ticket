<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('theatres', 'is_active')) {
            Schema::table('theatres', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('total_seats');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('theatres', 'is_active')) {
            Schema::table('theatres', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
