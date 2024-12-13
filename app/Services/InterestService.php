<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InterestService
{
    public function CreateUserInterest($userId, $categoryId): void
    {
        $user = User::findOrFail($userId);
        $user->interests()->syncWithoutDetaching([$categoryId => ['interest_level' => 0]]);
    }
    public function increaseInterestLevel($userId, $categoryId,$value): void
    {
        DB::table('user_category_interests')
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->increment('interest_level', $value);
    }
    public function decreaseInterestLevel($userId, $categoryId,$value): void
    {
        $this->increaseInterestLevel($userId, $categoryId, -1 * $value);
    }
    public function CheckUserInterest($userId, $categoryId): bool
    {
      return  DB::table('user_category_interests')
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->exists();
    }
    public function getRecommendedCategories($userId)
    {
        return User::find($userId)
            ->interests()
            ->orderBy('pivot_interest_level', 'desc')
            ->get();
    }
    public function recommendProducts(int $userId): array
    {
        // Retrieve the user's interests with positive interest levels
        $interests = DB::table('user_category_interests')
            ->where('user_id', $userId)
            ->where('interest_level', '>', 0)
            ->get(['category_id', 'interest_level']);

        if ($interests->isEmpty()) {
            return []; // No recommendations if no positive interests
        }

        // Calculate the total interest level to determine percentages
        $totalInterest = $interests->sum('interest_level');

        // Calculate the number of products to recommend per category
        $categoriesWithProductCount = $interests->mapWithKeys(function ($interest) use ($totalInterest) {
            $percentage = $interest->interest_level / $totalInterest;
            $productCount = (int) ceil($percentage * 100); // Determine number of products for this category
            return [$interest->category_id => $productCount];
        });

        // Fetch products for each category
        $recommendedProducts = [];

        foreach ($categoriesWithProductCount as $categoryId => $productCount) {
            if ($productCount > 0) {
                $products = DB::table('products')
                    ->where('category_id', $categoryId)
                    ->limit($productCount)
                    ->get();
                $recommendedProducts = array_merge($recommendedProducts, $products->toArray());
            }
        }

        return $recommendedProducts;
    }
    public function ChangeChecked($userId, $categoryId,$status): void
    {
        DB::table('user_category_interests')
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->update(['checked'=>$status]);
    }
    public function InterestStatus($userId, $categoryId)
    {
        return DB::table('user_category_interests')
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->firstOrFail()->checked;
    }

}
