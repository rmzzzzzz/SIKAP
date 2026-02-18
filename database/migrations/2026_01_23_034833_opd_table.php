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
    Schema::create('opd', function (Blueprint $table) {
        $table->id('id_opd');
        $table->string('nama_opd', 150);
        $table->string('alamat', 255);
        $table->string('telp', 20);
        $table->string('email', 100);
        $table->string('website', 100);
        $table->timestamps();
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
