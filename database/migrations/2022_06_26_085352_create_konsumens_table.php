<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonsumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konsumens', function (Blueprint $table) {
            $table->id();
            $table->string('konsumen_nama', 100);
            $table->string('konsumen_phone', 14);
            $table->string('konsumen_email', 255)->unique();
            $table->boolean('konsumen_blacklist')->default(0);
            $table->string('konsumen_foto', 255);
            $table->text('token');
            $table->enum('role', ['admin', 'umkm', 'konsumen'])->default('konsumen');
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konsumens');
    }
}
