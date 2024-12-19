<?php

use App\Models\Category;
use App\Models\Store;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Store::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('price');
            $table->unsignedTinyInteger('discount')->default(0)->checkbetween(0, 100)->comment('discount percentage % ');
            $table->unsignedInteger('quantity');
            $table->text('description')->nullable()->default(null);
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->date('expire_date')->nullable();
            $table->decimal('rate', 2, 1)->default(0)->checkbetween(0, 5);
            $table->unsignedInteger('sales')->default(0)->comment('number of sales for that product ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
