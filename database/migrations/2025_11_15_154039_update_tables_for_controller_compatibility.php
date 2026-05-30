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
        // Update transactions table
        Schema::table('transactions', function (Blueprint $table) {
            // Add transaction_number field
            $table->string('transaction_number')->unique()->after('id');
            // Rename amount_total to total_amount
            $table->renameColumn('amount_total', 'total_amount');
            // Rename payment_proof_url to payment_proof
            $table->renameColumn('payment_proof_url', 'payment_proof');
            // Add rejection_reason field
            $table->text('rejection_reason')->nullable()->after('verification_notes');
            // Update payment_status enum to include 'paid' and 'failed'
            $table->enum('payment_status', ['pending', 'paid', 'verified', 'failed', 'rejected'])->default('pending')->change();
        });

        // Update bookings table
        Schema::table('bookings', function (Blueprint $table) {
            // Add booking_type and price fields
            $table->enum('booking_type', ['subscription', 'dropin'])->default('dropin')->after('subscription_id');
            $table->decimal('price', 10, 2)->default(0)->after('booking_type');
            // Rename check_in_time to checked_in_at
            $table->renameColumn('check_in_time', 'checked_in_at');
            // Update status enum to include 'confirmed'
            $table->enum('status', ['confirmed', 'booked', 'cancelled', 'completed'])->default('confirmed')->change();
        });

        // Update bank_accounts table
        Schema::table('bank_accounts', function (Blueprint $table) {
            // Add branch field if it doesn't exist
            if (!Schema::hasColumn('bank_accounts', 'branch')) {
                $table->string('branch')->nullable()->after('account_holder');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_number');
            $table->renameColumn('total_amount', 'amount_total');
            $table->renameColumn('payment_proof', 'payment_proof_url');
            $table->dropColumn('rejection_reason');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_type', 'price']);
            $table->renameColumn('checked_in_at', 'check_in_time');
        });

        Schema::table('bank_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('bank_accounts', 'branch')) {
                $table->dropColumn('branch');
            }
        });
    }
};
