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
        Schema::create('kategori_master_item', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('master_item_id');

            $table->primary(['kategori_id', 'master_item_id']);

            $table->foreign('kategori_id')
                ->references('id')
                ->on('kategoris')
                ->onDelete('cascade');

            $table->foreign('master_item_id')
                ->references('id')
                ->on('master_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_master_item');
    }
};
