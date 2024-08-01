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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 256);
            $table->string('description', 2048);
            $table->double('price')->nullable();
            $table->date('date');
            $table->string('time', 32);
            $table->string('address', 256);
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->string('ver_img_path', 512);
            $table->string('hor_img_path', 512);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
