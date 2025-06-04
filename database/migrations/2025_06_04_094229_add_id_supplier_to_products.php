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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('id_supplier')
                ->nullable() // Sesuaikan: jika id_supplier boleh kosong
                ->constrained('suppliers') // Menghubungkan ke tabel 'suppliers'
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_supplier'); // Laravel 8+ helper

            // Jika Anda menggunakan cara lama atau versi Laravel di bawah 8:
            // $table->dropForeign(['id_supplier']);
            // 2. Drop the column
            $table->dropColumn('id_supplier');
        });
    }
};
