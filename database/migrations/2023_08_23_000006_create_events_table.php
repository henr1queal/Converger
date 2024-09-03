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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('theme');
            $table->string('place');
            $table->text('observation');
            $table->decimal('total_price');
            $table->decimal('converger_price');
            $table->decimal('speaker_price');
            $table->decimal('partner_price');
            $table->date('note_emission_date');
            $table->date('payment_term');
            $table->date('receipt_date');
            $table->boolean('received');
            $table->date('speaker_payment_date');
            $table->text('financial_observation');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('event_type_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
