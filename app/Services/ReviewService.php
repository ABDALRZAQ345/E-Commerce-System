<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function AddReview(User $user, Product|Store $object, $rate, $comment = null): void
    {
        try {
            DB::beginTransaction();

            $review = $object->reviews()->where('user_id', $user->id)->first();
            if ($review != null) {
                $review->update([
                    'rate' => $rate,
                    'comment' => $comment,
                ]);
            } else {
                $object->reviews()->create([
                    'rate' => $rate,
                    'comment' => $comment,
                    'user_id' => $user->id,
                ]);
            }
            $object->rate = $object->reviews()->avg('rate');
            $object->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function GetReviews($object, $rate = null)
    {
        $query = $object->reviews();
        if ($rate != null) {
            $query = $query->where('rate', $rate);
        }

        return $query->paginate(20);
    }
}
