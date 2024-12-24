<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubOrder;
use App\Models\User;

class StatisticsController extends Controller
{
    public function get()
    {
        try {
            $totalSales = SubOrder::where('status', 'Delivered')->sum('total');
            $newUsers = User::whereBetween('created_at', [now()->subDays(7), now()])->count();
            $totalProducts = Product::sum('quantity');

            $deliveredOrders = SubOrder::where('status', 'Delivered')->count();
            $totalOrders = SubOrder::count();
            $conversionRate = $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 2) : 0;

            $maxCategoryId = Category::max('id');
            $numOfStores = Store::count();
            $categoryStorePercentage = [];

            for ($categoryId = 1; $categoryId <= $maxCategoryId; $categoryId++) {
                $category = Category::find($categoryId);

                if (! $category) {
                    continue;
                }

                $rowCount = \DB::table('category_store')
                    ->where('category_id', $categoryId)
                    ->count();

                $percentage = $numOfStores > 0 ? (float) round(($rowCount / $numOfStores) * 100, 1) : 0;
                $categoryStorePercentage[] = [
                    'category_name' => $category->name,
                    'percentage' => $percentage,
                ];
            }

            usort($categoryStorePercentage, function ($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });

            $numOfProducts = Product::count();
            $categoryProductPercentage = [];

            for ($categoryId = 1; $categoryId <= $maxCategoryId; $categoryId++) {
                $category = Category::find($categoryId);

                if (! $category) {
                    continue;
                }

                $rowCount = \DB::table('products')
                    ->where('category_id', $categoryId)
                    ->count();

                $percentage = $numOfStores > 0 ? (float) round(($rowCount / $numOfStores) * 100, 1) : 0.0;
                $categoryProductPercentage[] = [
                    'category_name' => $category->name,
                    'percentage' => $percentage,
                ];
            }
            usort($categoryProductPercentage, function ($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });

            $monthlySales = [];
            $currentMonth = now();

            for ($i = 0; $i < 6; $i++) {
                $startOfMonth = $currentMonth->copy()->subMonths($i)->startOfMonth();
                $endOfMonth = $currentMonth->copy()->subMonths($i)->endOfMonth();

                $sales = (float) SubOrder::where('status', 'Delivered')
                    ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                    ->sum('total');

                $monthlySales[] = [
                    'month' => $startOfMonth->format('F Y'),
                    'sales' => $sales,
                ];
            }

            $monthlySales = array_reverse($monthlySales);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_sales' => $totalSales,
                    'new_users' => $newUsers,
                    'total_products' => $totalProducts,
                    'conversion_rate' => round($conversionRate, 2),
                    'stores_percentage' => $categoryStorePercentage,
                    'products_percentage' => $categoryProductPercentage,
                    'monthly_sales' => $monthlySales,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
