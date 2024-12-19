<?php

namespace App\Http\Controllers\Category;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json(
                [
                    'status' => true,
                    'categories' => Category::all(),
                ]);
        } catch (\Exception $exception) {
            throw new ServerErrorException($exception->getMessage());
        }

    }
}
