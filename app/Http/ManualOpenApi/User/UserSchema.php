<?php

namespace App\Http\ManualOpenApi\User;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "first_name", "last_name", "phone_number", "created_at", "updated_at", "roles_status"},
 *
 *     @OA\Property(property="id", type="integer", description="The user's unique ID"),
 *     @OA\Property(property="first_name", type="string", description="The user's first name"),
 *     @OA\Property(property="last_name", type="string", description="The user's last name"),
 *     @OA\Property(property="phone_number", type="string", description="The user's phone number"),
 *     @OA\Property(property="photo", type="string", nullable=true, description="Profile photo URL or base64 encoded (optional)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of when the user was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of when the user was last updated"),
 *     @OA\Property(
 *         property="roles_status",
 *         type="object",
 *         description="The user's roles status",
 *         required={"manager", "admin", "user"},
 *         @OA\Property(property="manager", type="boolean", description="Indicates if the user is a manager"),
 *         @OA\Property(property="admin", type="boolean", description="Indicates if the user is an admin"),
 *         @OA\Property(property="user", type="boolean", description="Indicates if the user is a regular user")
 *     )
 * )
 */
class UserSchema {}
