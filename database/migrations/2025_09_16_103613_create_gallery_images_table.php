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
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imageable_id'); // id dari model (Product / Supplier)
            $table->string('imageable_type'); // nama model (App\Models\Product / Supplier)
            $table->string('file_path'); // path di storage
            $table->string('original_name');
            $table->timestamps();

            $table->index(['imageable_id', 'imageable_type']); // untuk performa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
