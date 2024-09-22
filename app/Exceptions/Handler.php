<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Captura erros de autenticação JWT
        if ($exception instanceof TokenExpiredException) {
            return response()->json(['error' => 'O token expirou, faça login novamente.'], 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token inválido.'], 401);
        }

        if ($exception instanceof JWTException) {
            return response()->json(['error' => 'Token não fornecido.'], 401);
        }

        // Validação de campos
        if ($exception instanceof ValidationException) {
            return response()->json(['errors' => $exception->validator->errors()], 422);
        }

        // Captura erro 404 para recursos não encontrados
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Recurso não encontrado.'], 404);
        }

        // Captura erro 500 - Erros internos
        if ($exception instanceof \Exception) {
            return response()->json(['message' => 'Erro interno do servidor.'], 500);
        }

        return parent::render($request, $exception);
    }
}
