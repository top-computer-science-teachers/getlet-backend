<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json(UserResource::make($users), 200);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->create($data);

        return response()->json([
            'message' => 'User created successfully',
            'data' => UserResource::make($user),
        ], 201);
    }

    public function show(String $id): JsonResponse
    {
        $user = User::query()->find($id);

        return response()->json(UserResource::make($user), 200);
    }

    public function update(String $id, UpdateUserRequest $request): JsonResponse
    {
        $user = User::query()->find($id);

        $data = $request->validated();

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => UserResource::make($user)
        ], 200);
    }

    public function destroy(String $id): JsonResponse
    {
        $user = User::query()->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
