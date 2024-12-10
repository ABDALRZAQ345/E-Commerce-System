<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RateService
{
    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function Rate(User $user, Product|Store $object, $rate,$comment=null): void
    {
        try {
            DB::beginTransaction();

            $object->rates()->where('user_id', $user->id)->delete();
            $object->rates()->create([
                'rate' => $rate,
                'comment' => $comment,
                'user_id' => $user->id,
            ]);
            $object->rate = $object->rates()->avg('rate');
            $object->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }
}
