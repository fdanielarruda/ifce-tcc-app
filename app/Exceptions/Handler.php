<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
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

        $currentUrl = $request->fullUrl();
        $errorKey = 'last_error_url';
        $countKey = 'error_redirect_count';

        $lastErrorUrl = Session::get($errorKey);
        $redirectCount = Session::get($countKey, 0);

        if ($lastErrorUrl === $currentUrl && $redirectCount >= 2) {
            Session::forget([$errorKey, $countKey]);

            return redirect()
                ->route($this->getDefaultRoute())
                ->with('error', $e->getMessage() ?? 'Erro ao realizar requisição');
        }

        if ($lastErrorUrl === $currentUrl) {
            Session::put($countKey, $redirectCount + 1);
        } else {
            Session::put($errorKey, $currentUrl);
            Session::put($countKey, 1);
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
