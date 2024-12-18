<?php

/**
 * get the minimum element not in the array
 */

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

if (! function_exists('EmptyPagination')) {

    function EmptyPagination(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            Collection::make([]), // Empty collection
            0,                    // Total items
            20,                   // Per page
            1,                    // Current page
            ['path' => request()->url(), 'query' => request()->query()] // For consistent pagination links
        );
    }

}

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
