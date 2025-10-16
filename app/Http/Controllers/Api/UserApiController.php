<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserDeleteRequest;
use App\Http\Requests\Users\UserIndexRequest;
use App\Http\Requests\Users\UserStoreRequest;
use App\Services\UserService;

class UserApiController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    public function index(UserIndexRequest $request)
    {
        $data = $request->validated();

        return response()->json([
            'users' => $this->service->list($data)
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $user = $this->service->create($data);

        return response()->json([
            'message' => 'Usuário salvo com sucesso',
            'user' => $user
        ]);
    }

    public function delete(UserDeleteRequest $request)
    {
        $data = $request->validated();
        $this->service->delete($data);

        return response()->json([
            'message' => 'Usuário deletado com sucesso'
        ]);
    }
}
