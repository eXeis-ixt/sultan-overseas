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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('sl');
            $table->string('name');
            $table->string('passport_number');
            $table->string('passport_expiration_date')->nullable();
            $table->string('status')->default('new');
            $table->bigInteger('due')->nullable();
            $table->bigInteger('total')->nullable();
            $table->string('embassy_date')->nullable();
            $table->string('delivery_date')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
