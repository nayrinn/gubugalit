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
        Schema::create('detail_anggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggaran_id')->constrained('anggaran')->onDelete('cascade');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->onDelete('cascade');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_anggaran');
    }
};
