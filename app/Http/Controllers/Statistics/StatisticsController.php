<?php

namespace App\Http\Controllers\Statistics;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
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

            $totalSales =   cache()->remember('total_sales',3600, function () {
                return $this->statisticsService->TotalSales();
            });

            $newUsers =cache()->remember('newUsers','3600',function (){
                return $this->statisticsService->NewStores();
            });
            $totalProducts =cache()->remember('total_products','3600',function (){
                return $this->statisticsService->TotalProducts();
            });
            $conversionRate = cache()->remember('conversation','3600',function (){
                return   $this->statisticsService->ConversionRate();
            });

            $categoryStorePercentage = cache()->remember('categoryStorePercentage','3600',function (){
                return $this->statisticsService->CategoryStorePercentage();
            });

            $categoryProductPercentage = cache()->remember('CategoryProductPercentage','3600',function (){
                return    $this->statisticsService->CategoryProductPercentage();
            });

            $monthlySales = cache()->remember('MonthlySales','3600',function (){
                return $this->statisticsService->MonthlySales();
            });


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
            throw new ServerErrorException('Something went wrong ');
        }
    }
}
