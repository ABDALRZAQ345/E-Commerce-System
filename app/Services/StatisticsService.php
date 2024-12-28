<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Exceptions\ServerErrorException;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubOrder;
use App\Models\User;

class StatisticsService
{
    /**
     * @throws ServerErrorException
     */
    public function TotalSales()
    {
        return SubOrder::where('status', OrderStatusEnum::Delivered)->sum('total');
    }

    public function NewStores(): int
    {
        return User::whereBetween('created_at', [now()->subDays(7), now()])->count();
    }

    public function TotalProducts(): int
    {
        return Product::sum('quantity');
    }

    public function ConversionRate(): float|int
    {
        $deliveredOrders = SubOrder::where('status', OrderStatusEnum::Delivered)->count();
        $totalOrders = SubOrder::count();

        return $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 2) : 0;
    }

    public function CategoryStorePercentage(): array
    {

        $numOfStores = Store::count();
        $categoryStorePercentage = [];
        $count = \DB::table('category_store')->count();
        foreach (Category::all() as $category) {

            $rowCount = \DB::table('category_store')
                ->where('category_id', $category->id)
                ->count();

            $percentage = $numOfStores > 0 ? round(($rowCount / $count) * 100, 1) : 0;

            $categoryStorePercentage[] = [
                'category_name' => $category->name,
                'percentage' => $percentage,
            ];

        }

        usort($categoryStorePercentage, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return $categoryStorePercentage;
    }

    public function CategoryProductPercentage(): array
    {

        $categoryProductPercentage = [];
        $numOfProducts = \DB::table('products')->count();
        foreach (Category::all() as $category) {

            $rowCount = \DB::table('products')
                ->where('category_id', $category->id)
                ->count();

            $percentage = $numOfProducts > 0 ? round(($rowCount / $numOfProducts) * 100, 1) : 0.0;
            $categoryProductPercentage[] = [
                'category_name' => $category->name,
                'percentage' => $percentage,
            ];
        }
        usort($categoryProductPercentage, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return $categoryProductPercentage;
    }

    public function MonthlySales(): array
    {
        $monthlySales = [];
        $currentMonth = now();

        for ($i = 0; $i < 6; $i++) {
            $startOfMonth = $currentMonth->copy()->subMonths($i)->startOfMonth();
            $endOfMonth = $currentMonth->copy()->subMonths($i)->endOfMonth();

            $sales = (float) SubOrder::where('status', OrderStatusEnum::Delivered)
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->sum('total');

            $monthlySales[] = [
                'month' => $startOfMonth->format('F Y'),
                'sales' => $sales,
            ];
        }

        $monthlySales = array_reverse($monthlySales);

        return $monthlySales;
    }
}
