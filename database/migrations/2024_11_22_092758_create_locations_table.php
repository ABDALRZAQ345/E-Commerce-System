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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude', 10, 8);
            $table->decimal('latitude', 10, 8);
            $table->string('name', 50);
            $table->unsignedBigInteger('object_id')->nullable();
            $table->string('object_type')->nullable();
            $table->index(['object_id', 'object_type']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
