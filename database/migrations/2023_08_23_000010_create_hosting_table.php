<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hostings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->dateTimeTz('check_in');
            $table->dateTimeTz('check_out');
            $table->string('cep');
            $table->string('city');
            $table->string('address');
            $table->string('neighborhood');
            $table->integer('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('reference_point')->nullable();
            $table->text('observation')->nullable();
            $table->unsignedBigInteger('event_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostings');
    }
};
