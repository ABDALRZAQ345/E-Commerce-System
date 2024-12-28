<?php

/**
 * get the minimum element not in the array
 */
if (! function_exists('NewPublicPhoto')) {
    function NewPublicPhoto($photo, $folder = 'photos'): string
    {
        $photoPath = $photo->store($folder, 'public');
        $photoPath = 'storage/'.$photoPath;

        return $photoPath;
    }
}
if (! function_exists('DeletePublicPhoto')) {
    function DeletePublicPhoto($path): void
    {
        $oldPhotoPath = str_replace('storage/', 'public/', $path);
        \Storage::delete($oldPhotoPath);
    }

}
