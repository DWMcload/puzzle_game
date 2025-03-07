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
        Schema::create('high_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('puzzle_id');
            $table->integer('score');
            $table->string('words');
            $table->timestamps();
            $table->foreign('puzzle_id')->references('id')->on('puzzles')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('high_scores');
    }
};
