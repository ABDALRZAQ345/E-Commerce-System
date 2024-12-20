<?php

use App\Enums\OrderStatusEnum;
use App\Models\Location;
use App\Models\Order;
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
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained();
            $table->enum('status', OrderStatusEnum::getAllStatus())->default(OrderStatusEnum::Pending);
            $table->decimal('total', 10, 2)->comment('Total price for the sub-order');
            $table->foreignIdFor(Store::class)->constrained();
            $table->foreignIdFor(Location::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_orders');
    }
};
