<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

abstract class Controller
{
    /**
     * @OA\Schema(
     *     schema="Location",
     *     type="object",
     *
     *     @OA\Property(property="id", type="integer", description="The unique identifier for the location"),
     *     @OA\Property(property="latitude", type="number", format="float", description="Latitude of the location"),
     *     @OA\Property(property="longitude", type="number", format="float", description="Longitude of the location"),
     *     @OA\Property(property="name", type="string", maxLength=50, description="Name of the location")
     * )
     */

    //
}
