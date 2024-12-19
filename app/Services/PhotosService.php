<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PhotosService
{

    /**
     * @throws \Throwable
     */
    public function AddPhotos($images, $object): void
    {
        try {
            //todo make it in job
            DB::beginTransaction();
            foreach ($images as $image) {

                $path = NewPublicPhoto($image, 'products_photos');
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
        // todo make it in job
        try {
            DB::beginTransaction();
            $oldPhotos = $object->photos;
            foreach ($oldPhotos as $photo) {
                DeletePublicPhoto($photo->photo);
            }
            $object->photos()->delete();
            $object->save();
            DB::commit();
        }
        catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }


    }
}
