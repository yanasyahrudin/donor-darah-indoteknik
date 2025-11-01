<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('golongan_darah', 10);
            $table->string('whatsapp');
            $table->string('ticket_code')->unique();
            $table->enum('session', [
                'sesi_1',
                'sesi_2',
                'sesi_3',
                'sesi_4',
                'sesi_5'
            ]);
            $table->integer('umur')->nullable();
            $table->boolean('umur_valid')->default(false);
            $table->boolean('sehat')->default(false);
            $table->boolean('is_sent')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
