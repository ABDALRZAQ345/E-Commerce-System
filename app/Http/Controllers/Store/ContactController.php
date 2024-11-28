<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function index(Store $store): JsonResponse
    {
        $contacts = $store->contacts;

        return response()->json([
            'contacts' => $contacts,
        ]);
    }

    public function store(Request $request, Store $store): JsonResponse
    {
        if ($store->contacts()->count() >= 5) {
            return response()->json([
                'message' => 'you cant add more than 5  contacts ',
            ], 400);
        }

        $validated = $request->validate([
            'type' => ['string', 'required'],
            'value' => ['string', 'required'],
        ]);
        $store->contacts()->create($validated);

        return response()->json([
            'message' => 'contact created successfully',
        ]);
    }

    public function delete(Store $store, Contact $contact): JsonResponse
    {
        $contact = $store->contacts()->findOrFail($contact->id);
        $contact->delete();

        return response()->json([
            'message' => 'contact deleted successfully',
        ]);
    }

    public function update(Request $request, Store $store, Contact $contact): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['string', 'required'],
            'value' => ['string', 'required'],
        ]);
        $contact=$store->contacts()->findOrFail($contact->id);
        $contact->update($validated);

        return response()->json([
            'message' => 'contact updated successfully',
        ]);
    }
}
