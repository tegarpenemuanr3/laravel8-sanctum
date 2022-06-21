<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmkmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->string('umkm_nama', 255);
            $table->string('umkm_phone', 14);
            $table->string('umkm_email', 255)->unique();
            $table->text('umkm_alamat');
            $table->text('umkm_latitude');
            $table->text('umkm_longtitude');
            $table->text('umkm_deskripsi');
            $table->string('umkm_operasional', 255);
            $table->date('umkm_buka_sejak');
            $table->string('umkm_foto', 255);
            $table->string('umkm_pemilik_nama', 255);
            $table->string('umkm_pemilik_phone', 14);
            $table->enum('umkm_status', ['aktif', 'non aktif', 'review', 'blacklist', 'tolak'])->default('review');
            $table->enum('role', ['admin', 'umkm', 'konsumen'])->default('umkm');
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
        Schema::dropIfExists('umkms');
    }
}
