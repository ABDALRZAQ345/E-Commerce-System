<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Models\Store;

class StoreCategoryService extends CategoryService
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
        parent::__construct(Store::class, $id);
    }

    /**
     * @throws \Throwable
     */
    public function UpdateCategories(array $categoryIds): void
    {
        try {
            $store = Store::findOrFail($this->id);
            \DB::beginTransaction();
            $store->categories()->detach();
            $categories = Category::whereIn('id', $categoryIds)->get();
            $store->categories()->attach($categories);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception('Categories not updated');
        }

    }

    /**
     * @throws \Throwable
     */
    public function AddCategories(array $categoryIds): void
    {
        try {
            $store = Store::findOrFail($this->id);
            \DB::beginTransaction();
            $store->categories()->detach();
            $categories = Category::whereIn('id', $categoryIds)->get();
            $store->categories()->attach($categories);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception('Categories not added');
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw new \Exception('Categories not updated');
        }

    }
}
