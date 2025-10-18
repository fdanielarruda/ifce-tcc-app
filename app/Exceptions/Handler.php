<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()) {
            return parent::render($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return redirect()->route('login');
        }

        if ($e instanceof ValidationException) {
            return redirect()->back()->with('error', $e->getMessage() ?? 'Erro ao validar dados da requisição');
        }

        return back()->with('error', $e->getMessage() ?? 'Erro ao realizar requisição');
    }

    protected function getDefaultRoute(): string
    {
        if (auth()->check()) {
            return 'dashboard';
        }

        return 'login';
    }
}
