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
            $table->string('name');
            $table->unsignedBigInteger('quantity');
            $table->decimal('price', 10, 2)->comment('price per item');
            $table->decimal('total', 10, 2)->comment('total price');
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
