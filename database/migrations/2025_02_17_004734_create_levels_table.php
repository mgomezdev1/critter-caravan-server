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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('name');
            $table->boolean('private');
            $table->longText('thumbnail')->nullable();
            $table->string('category')->nullable();
            $table->integer('verification_level')->default(0);
            $table->longText('world');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
