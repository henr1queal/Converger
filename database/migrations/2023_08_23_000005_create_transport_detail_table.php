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
        Schema::create('transport_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table
                ->boolean('bridge')
                ->default(false)
                ->nullable();
            $table->string('company')->nullable();
            $table->integer('number')->nullable();
            $table->string('seat')->nullable();
            $table->string('origin_airport')->nullable();
            $table->string('destiny_airport')->nullable();
            $table->date('date');
            $table->time('start_boarding')->nullable();
            $table->time('arrival_forecast')->nullable();
            $table->text('observation')->nullable();
            $table->unsignedBigInteger('transport_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_details');
    }
};
