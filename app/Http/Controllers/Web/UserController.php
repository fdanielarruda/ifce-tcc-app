<?php

namespace App\Http\Controllers\Web;

use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\FirstAccessRequest;
use App\Http\Requests\Users\UserDeleteRequest;
use App\Http\Requests\Users\UserStoreRequest;
use App\Mail\AccessCodeMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ) {}

    public function index(Request $request)
    {
        $users = $this->service->list($request->all());

        return Inertia::render('Users/UserIndex', [
            'users' => $users
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = $this->service->create($request->validated());

            return back()->with('success', "Usuário criado com sucesso! Código de acesso: {$user['code']}");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(UserDeleteRequest $request)
    {
        try {
            $this->service->delete($request->validated());

            return back()->with('success', 'Usuário deletado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function firstAccess()
    {
        return Inertia::render('Auth/FirstAccess');
    }

    public function processFirstAccess(FirstAccessRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();

            $code = StringHelper::newCode(8);
            $user->password = Hash::make($code);
            $user->save();

            Mail::to($user->email)->send(new AccessCodeMail($code));

            return back()->with('success', 'Um novo código de acesso foi enviado para seu email!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Ocorreu um erro ao enviar o código. Tente novamente.']);
        }
    }
}
