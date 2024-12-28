<?php

namespace App\Http\Controllers\Statistics;

use App\Enums\OrderStatusEnum;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubOrder;
use App\Models\User;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    protected StatisticsService $statisticsService;
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * @throws ServerErrorException
     */
    public function get(): JsonResponse
    {
        try {
            $totalSales = $this->statisticsService->TotalSales();
            $newUsers = $this->statisticsService->NewStores();
            $totalProducts = $this->statisticsService->TotalProducts();
            $conversionRate = $this->statisticsService->ConversionRate();
            $categoryStorePercentage=$this->statisticsService->CategoryStorePercentage();
            $categoryProductPercentage=$this->statisticsService->CategoryProductPercentage();
            $monthlySales=$this->statisticsService->MonthlySales();

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
           Throw new ServerErrorException("Something went wrong ");
        }
    }
}
