<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCategoryRequest;
use App\Models\User;
use App\Services\InterestService;

class UserInterestController extends Controller
{
    protected InterestService $interestService;

    public function __construct(InterestService $interestService)
    {
        $this->interestService = $interestService;
    }

    public function store(User $user, UserCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $categories = $validated['categories'];
        foreach ($categories as $category) {
            if (! $this->interestService->CheckUserInterest($user->id, $category)) {
                $this->interestService->CreateUserInterest($user->id, $category);
                $this->interestService->increaseInterestLevel($user->id, $category, 10);
                $this->interestService->ChangeChecked($user->id, $category, true);
            } elseif (! $this->interestService->InterestStatus($user->id, $category)) {
                $this->interestService->increaseInterestLevel($user->id, $category, 10);
                $this->interestService->ChangeChecked($user->id, $category, true);
            }

        }

        $user->interests()->whereNotIn('id', $categories)->get()->each(function ($item) use ($user) {
            if ($this->interestService->InterestStatus($user->id, $item->id)) {
                $this->interestService->decreaseInterestLevel($user->id, $item->id, 10);
            }

            $this->interestService->ChangeChecked($user->id, $item->id, false);
        });

        return response()->json([
            'status' => true,
            'categories' => $user->interests,
        ]);
    }

    public function index(User $user): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'categories' => $user->interests,
        ]);
    }
}
