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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('rating', 10)->nullable();
            $table->integer('duration')->nullable();
            $table->string('genre', 50)->nullable();
            $table->date('release_date')->nullable();
            $table->string('trailer_url')->nullable();
            $table->text('cast')->nullable();
            $table->text('crew')->nullable();
            $table->string('formats')->default('2D,3D');
            $table->string('languages')->default('English');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
