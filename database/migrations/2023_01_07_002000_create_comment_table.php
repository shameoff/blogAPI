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
        Schema::create('comment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('user');
            $table->foreignUuid('post_id')->constrained('post');
            $table->uuid("parent_id")->nullable();
            $table->text('content');
            $table->timestamps(false);
            $table->softDeletes();
        });

        Schema::table('comment', function (Blueprint $table){
            $table->foreign('parent_id')->references("id")->on('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment');
    }
};
