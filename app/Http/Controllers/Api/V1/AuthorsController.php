<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthorsController extends ApiController
{
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::select('users.*')
            ->join('tickets', 'user.id', '=', 'tickets.user_id')
            ->filter($filters)
                ->distinct()
                ->paginate()
        );
    }

    public function store(StoreUserRequest $request)
    {
        //
    }

    public function show(User $author)
    {
        if ($this->include('tickets')) {
            return new UserResource($author->load('tickets'));
        }

        return new UserResource($author);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
