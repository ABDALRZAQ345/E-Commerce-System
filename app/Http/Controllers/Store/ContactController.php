<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactRequest;
use App\Models\Contact;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Mockery\Exception;

class ContactController extends Controller
{
    public function index(Store $store): JsonResponse
    {
        $contacts = $store->contacts;

        return response()->json([
            'status' => true,
            'message' => 'contacts retrieved successfully',
            'count' => count($contacts),
            'contacts' => $contacts,
        ]);
    }

    /**
     * @throws BadRequestException
     */
    public function store(ContactRequest $request, Store $store): JsonResponse
    {
        if ($store->contacts()->count() >= 5) {
            throw new BadRequestException('you cant add more than 5  contacts ');
        }

        $validated = $request->validated();
        $store->contacts()->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'contact created successfully',
        ]);
    }

    public function delete(Store $store, Contact $contact): JsonResponse
    {
        $contact = $store->contacts()->findOrFail($contact->id);
        $contact->delete();

        return response()->json([
            'status' => true,
            'message' => 'contact deleted successfully',
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function update(ContactRequest $request, Store $store, Contact $contact): JsonResponse
    {
        $validated = $request->validated();
        $contact = $store->contacts()->findOrFail($contact->id);
        try {
            $contact->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'contact updated successfully',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
