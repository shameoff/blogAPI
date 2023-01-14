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
        Schema::create('user', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('fullName');
            $table->date('birthDate')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
};
