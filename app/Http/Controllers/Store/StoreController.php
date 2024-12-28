<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Models\Store;
use App\Services\PhotosService;
use App\Services\ReviewService;
use App\Services\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    protected StoreService $storeService;

    protected PhotosService $photosService;

    protected ReviewService $rateService;

    public function __construct(StoreService $storeService, ReviewService $rateService, PhotosService $photosService)
    {
        $this->storeService = $storeService;
        $this->rateService = $rateService;
        $this->photosService = $photosService;
    }

    /**
     * @throws ServerErrorException
     */
    public function index(Request $request): JsonResponse
    {
        try {

            if ($request->has('search') && $request->search != null) {
                $stores = Store::search($request->input('search'))->paginate(20);
            } else {
                $stores = Store::filter($request->input('filter'))->paginate(20);
            }
            $user = Auth::user();
            foreach ($stores as $store) {
                $this->storeService->get_the_user_info_for_store($store, $user);
            }

            return response()->json([
                'status' => true,
                'message' => 'stores retrieved successfully',
                'stores' => $stores,
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    public function show(Store $store): JsonResponse
    {
        $store = Store::findOrFail($store->id);

        $store->load(['contacts', 'locations', 'categories', 'photos']);
        $user = Auth::user();
        $this->storeService->get_the_user_info_for_store($store, $user);

        return response()->json([
            'status' => true,
            'message' => 'stores retrieved successfully',
            'store' => $store,

        ]);
    }

    public function delete(Store $store): JsonResponse
    {
        try {
            //Todo check there is no uncompleted orders

            $store->delete();

            return response()->json([
                'message' => 'deleted successfully',
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ]);
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function store(StoreStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        try {

            if ($request->hasFile('photo')) {
                $validated['photo'] = NewPublicPhoto($request->file('photo'), 'stores');
            }
            $data = Arr::except($validated, 'photos');
            $store = $user->store()->create($data);
            //            if ($validated['photos']!=null )
            //            $this->photosService->AddPhotos($validated['photos'], $store);

            return response()->json([
                'status' => true,
                'message' => 'store created successfully',
            ], 201);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(UpdateStoreRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();

        try {
            if ($request->hasFile('photo')) {
                DeletePublicPhoto($store->photo);
                $validated['photo'] = NewPublicPhoto($request->file('photo'), 'stores');
            }
            $data = Arr::except($validated, 'photos');
            $store->update($data);
            if ($validated['photos'] != null) {
                $this->photosService->DeletePhotos($store);
                $this->photosService->AddPhotos($validated['photos'], $store);
            }

            return response()->json([
                'status' => true,

                'message' => 'store updated successfully',
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    public function audits(Store $store): JsonResponse
    {
        $audits = $store->audits()->paginate(20);

        return response()->json([
            'audits' => $audits,
        ]);
    }
}
