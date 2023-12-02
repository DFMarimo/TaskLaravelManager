<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->boolean('status')->default(1);
            $table->integer('score')->default(10);
            $table->integer('result_count')->default(0);
            $table->unsignedBigInteger('expertise_id');
            $table->text('description');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->foreign('expertise_id')->references('id')->on('expertises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
