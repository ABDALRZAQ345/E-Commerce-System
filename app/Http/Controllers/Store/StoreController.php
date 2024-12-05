<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreStoreRequest;
use App\Http\Requests\Store\UpdateStoreRequest;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $stores = Store::search($search)->paginate(20);

        return response()->json([
            'stores' => $stores,
        ]);
    }

    public function show(Store $store): JsonResponse
    {
        $store = Store::findOrFail($store->id);
        $contacts = $store->contacts;
        $locations = $store->locations;
        $categories = $store->categories;

        return response()->json([
            'store' => $store,
            'contacts' => $contacts,
            'locations' => $locations,
            'categories' => $categories,
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

    public function store(StoreStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('stores', 'public');
                $validated['photo'] = 'storage/'.$photoPath;
            }
            $store = \DB::transaction(function () use ($user, $validated) {
                return $user->store()->create($validated);
            });

            return response()->json([
                'message' => 'store created successfully',
                'store' => $store,
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 500);
        }

    }

    public function update(UpdateStoreRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();

        try {
            if ($request->hasFile('photo')) {

                $photoPath = $request->file('photo')->store('stores', 'public');

                $validated['photo'] = 'storage/'.$photoPath;
            }
            \DB::transaction(function () use ($store, $validated) {
                $store->update($validated);
            });

            return response()->json([
                'message' => 'store updated successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ]);
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
