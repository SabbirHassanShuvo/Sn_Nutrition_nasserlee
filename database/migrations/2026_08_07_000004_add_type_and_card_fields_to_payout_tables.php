<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('payout_methods')) {
            Schema::table('payout_methods', function (Blueprint $table) {
                if (!Schema::hasColumn('payout_methods', 'type')) {
                    $table->enum('type', ['bank_account', 'debit_card'])->default('bank_account')->after('user_id');
                }
                if (!Schema::hasColumn('payout_methods', 'card_last_four')) {
                    $table->string('card_last_four')->nullable()->after('routing_number');
                }
                if (!Schema::hasColumn('payout_methods', 'card_type')) {
                    $table->string('card_type')->nullable()->after('card_last_four');
                }
                if (!Schema::hasColumn('payout_methods', 'expiry_date')) {
                    $table->string('expiry_date')->nullable()->after('card_type');
                }
                if (!Schema::hasColumn('payout_methods', 'fees_info')) {
                    $table->string('fees_info')->nullable()->after('expiry_date');
                }
                if (!Schema::hasColumn('payout_methods', 'delivery_time')) {
                    $table->string('delivery_time')->nullable()->after('fees_info');
                }
            });
        }

        if (Schema::hasTable('payout_requests')) {
            Schema::table('payout_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('payout_requests', 'type')) {
                    $table->enum('type', ['bank_account', 'debit_card'])->default('bank_account')->after('user_id');
                }
                if (!Schema::hasColumn('payout_requests', 'card_last_four')) {
                    $table->string('card_last_four')->nullable()->after('routing_number');
                }
                if (!Schema::hasColumn('payout_requests', 'card_type')) {
                    $table->string('card_type')->nullable()->after('card_last_four');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payout_methods')) {
            Schema::table('payout_methods', function (Blueprint $table) {
                $columns = ['type', 'card_last_four', 'card_type', 'expiry_date', 'fees_info', 'delivery_time'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('payout_methods', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('payout_requests')) {
            Schema::table('payout_requests', function (Blueprint $table) {
                $columns = ['type', 'card_last_four', 'card_type'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('payout_requests', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
