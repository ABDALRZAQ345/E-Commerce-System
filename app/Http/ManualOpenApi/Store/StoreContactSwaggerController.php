<?php

namespace App\Http\ManualOpenApi\Store;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Contact",
 *     type="object",
 *     required={"id", "store_id", "type", "value"},
 *
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the contact"),
 *     @OA\Property(property="store_id", type="integer", description="The ID of the store the contact belongs to"),
 *     @OA\Property(property="type", type="string", description="The type of contact (e.g., phone, email)"),
 *     @OA\Property(property="value", type="string", description="The contact value (e.g., phone number, email address)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the contact was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the contact was last updated"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Timestamp when the contact was soft deleted")
 * )
 */
class StoreContactSwaggerController
{
    /**
     * @OA\Get(
     *     path="/stores/{store}/contacts",
     *     summary="Get all contacts for a store",
     *     description="Retrieve all contacts associated with a specific store.",
     *     tags={"Stores", "Contacts"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of contacts retrieved successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="contacts", type="array", @OA\Items(ref="#/components/schemas/Contact"))
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Store not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Object not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="An error occurred while fetching the contacts")
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/stores/{store}/contacts",
     *     summary="Add a new contact to a store",
     *     description="Only the manager and owner of the store can add contacts. The store can have up to 5 contacts at most .",
     *     tags={"Stores", "Contacts"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"type", "value"},
     *
     *                 @OA\Property(property="type", type="string", description="The contact type (e.g., phone, email)"),
     *                 @OA\Property(property="value", type="string", description="The contact value (e.g., phone number or email address)")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Contact created successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Contact created successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Cannot add more than 5 contacts",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="You cannot add more than 5 contacts.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="User is not authorized to add contacts",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Delete(
     *     path="/stores/{store}/contacts/{contact}",
     *     summary="Delete a contact from a store",
     *     description="Only the manager and owner of the store can delete a contact.",
     *     tags={"Stores", "Contacts"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         description="The unique identifier of the contact",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Contact deleted successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Contact deleted successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="User is not authorized to delete contacts",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Contact not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Contact not found.")
     *         )
     *     )
     * )
     */
    public function delete() {}

    /**
     * @OA\Put(
     *     path="/stores/{store}/contacts/{contact}",
     *     summary="Update a contact for a store",
     *     description="Only the manager and owner of the store can update a contact.",
     *     tags={"Stores", "Contacts"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         description="The unique identifier of the contact",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="type", type="string", example="email"),
     *             @OA\Property(property="value", type="string", example="contact@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Contact updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Contact updated successfully."),
     *             @OA\Property(
     *                 property="contact",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="email"),
     *                 @OA\Property(property="value", type="string", example="contact@example.com")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="User is not authorized to update contacts",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Contact not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Contact not found.")
     *         )
     *     )
     * )
     */
    public function update() {}
}
