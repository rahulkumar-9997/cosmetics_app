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
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('name', 'firm_name');
            $table->string('contact_person')->nullable()->after('firm_name');
            $table->string('gst_no')->nullable()->after('customer_id');
            $table->string('country')->nullable()->after('gst_no');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('pin_code')->nullable()->after('city');
            $table->text('permanent_address')->nullable()->after('pin_code');
            $table->unsignedBigInteger('added_by')->nullable()->after('permanent_address');
            $table->tinyInteger('approval_status')->default(0)->after('status')->comment('0=pending, 1=approved');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
        // Revert changes
            $table->renameColumn('firm_name', 'name');
            $table->dropColumn([
                'contact_person',
                'gst_no',
                'country',
                'state',
                'city',
                'pin_code',
                'permanent_address',
                'added_by',
                'approval_status',
            ]);
        });
    }
};
