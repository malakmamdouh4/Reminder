<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('country_key')->nullable();
            $table->string('password');
            $table->string('date_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('status', [ "block" , "pending", "active" ])->default('pending');
            $table->string('code')->nullable();
            $table->enum('type', ['family','patient','care_giver'])->default('patient');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->text('address')->nullable();
            $table->string('lang')->default('ar')->nullable();
            $table->enum('complete_patient_info', ['true', 'false'])->nullable();
            $table->enum('complete_giver_info', ['true', 'false'])->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
