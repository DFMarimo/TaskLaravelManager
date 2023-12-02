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
        Schema::create('expertises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('alt')->default('none');
            $table->bigInteger('user_count')->default(0);
            $table->bigInteger('task_count')->default(0);
            $table->bigInteger('parent_id')->default(0);
        });

        Schema::create('expertise_user', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('expertise_id');
            $table->foreign('expertise_id')->references('id')->on('expertises')->onDelete('cascade');
            $table->boolean('is_main_expertise')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expertise_user');
        Schema::dropIfExists('expertises');
    }
};
