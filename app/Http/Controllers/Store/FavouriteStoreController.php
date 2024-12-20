<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\FavouriteStore;
use App\Models\Store;
use App\Services\StoreService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavouriteStoreController extends Controller
{
    protected StoreService $storeService;
    public function __construct(StoreService $storeService){
        $this->storeService = $storeService;
    }
    /**
     * @throws ServerErrorException
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $favourites = $user->favouriteStores()->paginate(20);
            foreach ($favourites as $store) {
                $this->storeService->get_the_user_info_for_store($store, $user);
            }
            return response()->json([
                'status' => true,
                'message' => 'favourite list retrieved successfully',
                'stores' => $favourites,
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws BadRequestException
     * @throws ServerErrorException
     */
    public function store(Store $store): JsonResponse
    {
        $user = Auth::user();

        if (FavouriteStore::where('user_id', $user->id)->where('store_id', $store->id)->first()) {
            throw new BadRequestException('Store is already in your favourite list');
        }

//        if ($user->favouriteStores()->count() == config('app.data.max_favourites')) {
//            throw new BadRequestException('you cant add more than 100 favourite stores');
//        }
        try {

            $user->favouriteStores()->attach($store);

            return response()->json([
                'status' => true,
                'message' => 'Store added to favourites',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function delete(Store $store): JsonResponse
    {

        try {
            $user = Auth::user();
            $store = $user->favouriteStores()->findOrFail($store->id);
            $user->favouriteStores()->detach($store);

            return response()->json([
                'status' => true,
                'message' => 'Store deleted from favourite list',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
