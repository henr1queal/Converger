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
        Schema::table('event_supplier', function (Blueprint $table) {
            $table
                ->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_supplier', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['event_id']);
        });
    }
};
