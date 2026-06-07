<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE bookings MODIFY COLUMN status "
            ."ENUM('pending', 'confirmed', 'cancellation_requested', 'cancelled') "
            ."NOT NULL DEFAULT 'pending'"
        );
    }

    public function down(): void
    {
        DB::table('bookings')
            ->where('status', 'cancellation_requested')
            ->update(['status' => 'confirmed']);

        DB::statement(
            "ALTER TABLE bookings MODIFY COLUMN status "
            ."ENUM('pending', 'confirmed', 'cancelled') "
            ."NOT NULL DEFAULT 'pending'"
        );
    }
};
