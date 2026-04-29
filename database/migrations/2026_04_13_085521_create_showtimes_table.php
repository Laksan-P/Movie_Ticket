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
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theatre_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->dateTime('showtime');
            $table->integer('available_seats');
            $table->decimal('ticket_price', 10, 2);
            $table->string('language')->default('English');
            $table->string('format')->default('2D');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['theatre_id', 'movie_id', 'showtime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
