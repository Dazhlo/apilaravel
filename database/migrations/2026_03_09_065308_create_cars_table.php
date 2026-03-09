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
      Schema::create('cars', function (Blueprint $table) {
    $table->id(); [cite: 47]
    $table->integer('user_id'); [cite: 48]
    $table->string('title'); [cite: 49]
    $table->string('description'); [cite: 50]
    $table->double('price'); [cite: 51]
    $table->integer('status'); [cite: 52]
    $table->timestamps(); [cite: 53]
}); [cite: 46]
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
