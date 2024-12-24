<?php

use App\Models\Category;
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
        Schema::create('user_category_interests', function (Blueprint $table) {

            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->integer('interest_level')->default(0);
            $table->boolean('checked')->default(false)->comment(' when the user check that category as his interests'); // when the user check that category as his interests
            $table->primary(['user_id', 'category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_category_interests');
    }
};
