<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Presenters\JsonPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * get users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->input('page') ?? 1;
        $perPage = $request->input('per_page') ?? 20;

        $query = User::query()->orderByDesc('updated_at');

        $search = $request->input('search') ?? null;
        if ($search) {
            $query->where('firstname', 'like', ['%' . $search . '%'])
                ->orWhere('phone', 'like', ['%' . normalizePhone($search) . '%']);
        }

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return JsonPresenter::make()
            ->setData(UserResource::collection($users))
            ->setPagination($users)
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * create user
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['firstname'] = ucfirst($data['firstname']);
        $data['lastname'] = ucfirst($data['lastname']);
        $data['phone'] = normalizePhone($data['phone']);

        $user = User::query()->updateOrCreate([
            'phone' => $data['phone']
        ], $data);

        return JsonPresenter::make()
            ->setMessage('User created successfully')
            ->setData(UserResource::make($user))
            ->setStatusCode(201)
            ->respond();
    }

    /**
     * show user
     *
     * @param String $id
     * @return JsonResponse
     */
    public function show(String $id): JsonResponse
    {
        $user = User::query()->find($id);
        if (!$user) {
            return JsonPresenter::make()
                ->setError('User not found')
                ->setStatusCode(404)
                ->respond();
        }

        return JsonPresenter::make()
            ->setData(new UserResource($user))
            ->setStatusCode(200)
            ->respond();
    }

    /**
     * update user
     *
     * @param String $id
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(String $id, UpdateUserRequest $request): JsonResponse
    {
        $user = User::query()->find($id);
        if (!$user) {
            return JsonPresenter::make()
                ->setError('User not found')
                ->setStatusCode(404)
                ->respond();
        }

        $data = $request->validated();

        $user->update($data);

        return JsonPresenter::make()
            ->setMessage('User updated successfully')
            ->setData(new UserResource($user))
            ->respond();
    }

    /**
     * delete user
     *
     * @param String $id
     * @return JsonResponse
     */
    public function destroy(String $id): JsonResponse
    {
        $user = User::query()->find($id);
        if (!$user) {
            return JsonPresenter::make()
                ->setError('User not found')
                ->setStatusCode(404)
                ->respond();
        }

        $user->delete();

        return JsonPresenter::make()
            ->setMessage('User deleted successfully')
            ->respond();
    }
}
