<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kategori')
                ->references('id')
                ->on('kategoris')->onDelete('cascade');
            $table->string('name');
            $table->string('coordinates');
            $table->longText('deskripsi');
            $table->longText('image')->nullable();
            $table->integer('harga_tiket');
            $table->integer('jumlah_fasilitas');
            $table->float('ulasan');
            $table->integer('waktu_operasional');
            $table->tinyInteger('aksesibilitas');
            $table->longText('link_gmaps');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};
