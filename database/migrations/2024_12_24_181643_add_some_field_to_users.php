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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'doctor', 'patient'])->default('patient');
            $table->string('google_id')->nullable();
            $table->string('ktp_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->integer('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('certification')->nullable();
            $table->integer('telemedicine_fee')->nullable();
            $table->integer('chat_fee')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('set null');
            $table->foreignId('specialist_id')->nullable()->constrained('specialists')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
