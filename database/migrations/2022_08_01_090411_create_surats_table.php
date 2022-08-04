<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('jenis_surat')->nullable();
            $table->string('asal_surat')->nullable();
            $table->date('tgl_surat')->nullable();
            $table->string('perihal')->nullable();
            $table->date('tgl_acara')->nullable();
            $table->string('sifat_surat')->nullable();
            $table->string('surat')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('disposisi')->nullable();
            $table->string('created_by')->nullable();
            $table->string('edited_by')->nullable();
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
        Schema::dropIfExists('surats');
    }
}
