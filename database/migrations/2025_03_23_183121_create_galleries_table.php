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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')->constrained()->onDelete('cascade');

            $table->string('url')->unique();

            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('downloads')->default(0);
            $table->integer('likes')->default(0);

            $table->date('creation_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
