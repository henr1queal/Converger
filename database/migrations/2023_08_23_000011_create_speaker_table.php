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
        Schema::create('speakers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->date('birth_date');
            $table->string('cpf', 14);
            $table->string('rg', 9);
            $table->string('neighborhood');
            $table->string('cep');
            $table->string('address');
            $table->integer('number')->nullable();
            $table->string('city');
            $table->string('complement')->nullable();
            $table->string('email');
            $table->text('observation')->nullable();
            $table->integer('agency');
            $table->string('account');
            $table->string('pix')->nullable();
            $table->text('financial_observation')->nullable();
            $table->unsignedBigInteger('organization_type_id');
            $table->unsignedBigInteger('bank_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
    }
};
