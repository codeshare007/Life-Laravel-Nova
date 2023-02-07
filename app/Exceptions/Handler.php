<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class Handler extends ExceptionHandler
{
    const INTEGRITY_CONSTRAINT_VIOLATION = 23000;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Exception $exception)
    {
        // Kill reporting if this is an "access denied" (code 9) OAuthServerException. This is usually due to logout having revoked the access token.
        if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException && $exception->getCode() === 9) {
            return;
        }

        if (
            app()->environment('production') &&
            app()->bound('sentry') &&
            $this->shouldReport($exception)
        ) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Database\QueryException) {
            $exceptionCode = (int)$exception->errorInfo[0];

            if ($exceptionCode === static::INTEGRITY_CONSTRAINT_VIOLATION) {
                throw new IntegrityConstraintViolationException($exception->getMessage());
            }
        }

        if ($request->wantsJson()) {
            /**
             * @OA\Schema(
             *     schema="ModelNotFound",
             *     description="Model is either deleted or does not exist",
             *     type="object",
             *     title="Model not found",
             *     @OA\Property(
             *          property="message",
             *          type="string",
             *          description="Error message"
             *     ),
             * )
             */
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource couldn\'t be found'
                ], 404);
            }

            /**
             * @OA\Schema(
             *     schema="InvalidSortQuery",
             *     description="Sort query parameter is not allowed",
             *     type="object",
             *     title="Invalid Sort",
             *     @OA\Property(
             *          property="message",
             *          type="string",
             *          description="Error message"
             *     ),
             *     example="Given sorts(s) x are not allowed. Allowed sorts are y,z"
             * )
             */
            if ($exception instanceof InvalidSortQuery) {
                return response()->json([
                    'message' => $exception->getMessage()
                ], 400);
            }

            /**
             * @OA\Schema(
             *     schema="InvalidIncludeQuery",
             *     description="Include query parameter is not allowed",
             *     type="object",
             *     title="Invalid Include",
             *     @OA\Property(
             *          property="message",
             *          type="string",
             *          description="Error message"
             *     ),
             *     example="Given include(s) x are not allowed. Allowed includes are y,z"
             * )
             */
            if ($exception instanceof InvalidIncludeQuery) {
                return response()->json([
                    'message' => $exception->getMessage()
                ], 400);
            }
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (starts_with($request->route()->getName(), 'api')) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        return redirect('login');
    }

    protected function whoopsHandler()
    {
        try {
            return app(\Whoops\Handler\HandlerInterface::class);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            return parent::whoopsHandler();
        }
    }
}
