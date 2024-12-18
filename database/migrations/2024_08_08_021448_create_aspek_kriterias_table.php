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
        Schema::create('aspek_kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('persentase');
            $table->integer('bobot')->nullable();
            $table->integer('core_factory');
            $table->integer('secondary_factory');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspek_kriterias');
    }
};
