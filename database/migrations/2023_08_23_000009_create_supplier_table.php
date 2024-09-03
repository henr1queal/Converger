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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fantasy_name');
            $table->integer('organization_type');
            $table->string('cnpj', 18)->unique();
            $table->integer('municipal_registration');
            $table->string('cep');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('neighborhood');
            $table->integer('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('phone_number');
            $table->string('origin');
            $table->text('observation')->nullable();
            $table->string('agency');
            $table->integer('account');
            $table->string('pix')->nullable();
            $table->text('financial_observation')->nullable();
            $table->unsignedBigInteger('organization_type_id');
            $table->unsignedBigInteger('responsible_id');
            $table->unsignedBigInteger('bank_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
