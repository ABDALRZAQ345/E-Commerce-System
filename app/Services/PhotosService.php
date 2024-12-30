<?php

namespace App\Services;

use App\Jobs\AddPhotos;
use App\Models\Photo;
use Illuminate\Support\Facades\DB;

class PhotosService
{
    /**
     * @throws \Throwable
     */
    public function AddPhotos($images, $object,$folder='products_photos'): void
    {
        try {


            DB::beginTransaction();
            foreach ($images as $image) {

                $path = NewPublicPhoto($image, $folder);
                Photo::create([
                    'photo' => $path,
                    'object_id' => $object->id,
                    'object_type' => get_class($object),
                ]);

            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }

    /**
     * @throws \Throwable
     */
    public function DeletePhotos($object): void
    {
        try {
            DB::beginTransaction();
            $oldPhotos = $object->photos;
            foreach ($oldPhotos as $photo) {
                DeletePublicPhoto($photo->photo);
            }
            $object->photos()->delete();
            $object->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

    }
}
