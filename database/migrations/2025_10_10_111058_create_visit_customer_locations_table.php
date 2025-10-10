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
        Schema::create('visit_customer_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();            
            $table->unsignedBigInteger('customer_id')->nullable();            
            $table->string('ip_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->float('accuracy')->nullable();
            $table->float('altitude')->nullable();
            $table->float('altitude_accuracy')->nullable();
            $table->float('heading')->nullable();
            $table->float('speed')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_customer_locations');
    }
};
