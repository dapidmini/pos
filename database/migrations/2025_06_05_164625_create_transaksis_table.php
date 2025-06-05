<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice', 20);
            $table->dateTime('tanggal')->default(now());
            $table->string('nama_customer');
            $table->string('meja');
            $table->string('keterangan');
            $table->string('diskon')->nullable();
            $table->integer('total')->default(0);
            $table->string('status')->default('pending');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
