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
    Schema::create('kegiatan', function (Blueprint $table) {
        $table->id('id_kegiatan');
        $table->unsignedBigInteger('opd_id');
        $table->string('nama_kegiatan', 200);
        $table->unsignedBigInteger('pic');
        $table->dateTime('waktu');
        $table->string('lokasi', 255);
        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);
        $table->enum('akses_kegiatan',['satu opd','lintas opd']);
         $table->boolean('buat_kehadiran')->default(true);
        $table->timestamps();

        $table->foreign('opd_id')
            ->references('id_opd')
            ->on('opd')
            ->cascadeOnDelete();
        $table->foreign('pic')
            ->references('id_peserta')
            ->on('peserta')
            ->cascadeOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
