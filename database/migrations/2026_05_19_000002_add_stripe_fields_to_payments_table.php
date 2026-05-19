<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_gateway')) {
                $table->string('payment_gateway', 50)->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('payments', 'stripe_checkout_session_id')) {
                $table->string('stripe_checkout_session_id', 255)->nullable()->after('transaction_id');
            }
            if (! Schema::hasColumn('payments', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id', 255)->nullable()->after('stripe_checkout_session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['stripe_payment_intent_id', 'stripe_checkout_session_id', 'payment_gateway'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
