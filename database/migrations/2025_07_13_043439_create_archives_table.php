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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('location');
            $table->string('shelf_number', 50)->nullable();
            $table->string('box_number', 50)->nullable();
            $table->year('year')->nullable();
            $table->enum('status', ['available', 'borrowed', 'maintenance'])->default('available');
            $table->enum('condition', ['good', 'fair', 'poor'])->default('good');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
