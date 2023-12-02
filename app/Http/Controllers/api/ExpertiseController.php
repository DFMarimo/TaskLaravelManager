<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Expertise;
use App\Repositories\ExpertiseRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpertiseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index()
    {
        try {
            $expertise = resolve(ExpertiseRepository::class)->all();

            return response()->json([
                $expertise
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:64', 'unique:expertises,name'],
            'alt' => ['string', 'max:256'],
            'parent_id' => ['integer']
        ]);

        try {
            $expertise = resolve(ExpertiseRepository::class)->store($request->only(['name', 'alt', 'parent_id']));

            return response()->json([
                'message' => 'expertise store is completed.',
                'data' => $expertise
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function update(Request $request, Expertise $expertise)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:64', Rule::unique('expertises', 'name')->ignore($expertise->id)],
            'alt' => ['string', 'max:256'],
        ]);

        try {
            $newExpertise = resolve(ExpertiseRepository::class)
                ->update($request->only('name', 'alt', 'parent_id'), $expertise);

            return response()->json([
                'data' => $newExpertise,
                'message' => 'expertise store is completed.',
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }

    public function destroy(Expertise $expertise)
    {
        try {
            resolve(UserRepository::class)->destroy($expertise->id);
            return response()->json([
                'message' => 'expertise delete is completed.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Server Error',
            ], 500);
        }
    }
}
