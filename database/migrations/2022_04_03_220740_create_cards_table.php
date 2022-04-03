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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('first_edition');
            $table->string('serial_code');
            $table->enum('type', ['Monster', 'Magic', 'Trap']);
            $table->integer('attack');
            $table->integer('defense');
            $table->integer('star')->nullable();
            $table->unsignedDecimal('amount', $precision = 8, $scale = 2);
            $table->foreignId('subtype_id');
            $table->foreignId('image_id');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
