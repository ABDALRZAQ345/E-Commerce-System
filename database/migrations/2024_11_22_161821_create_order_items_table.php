<?php

use App\Models\Product;
use App\Models\SubOrder;
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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubOrder::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('price')->comment('price per item');
            $table->unsignedBigInteger('total')->comment('total price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
