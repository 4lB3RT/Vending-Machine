<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\infrastructure\Http\Api\Services;

use Illuminate\Http\JsonResponse as LaravelJsonResponse;
use Symfony\Component\HttpFoundation\Response;
use VendingMachine\Shared\Domain\Errors\Essentials\BadRequest;
use VendingMachine\Shared\Domain\Errors\Essentials\EntityNotFound;
use VendingMachine\Shared\Domain\Errors\Essentials\Forbidden;
use VendingMachine\Shared\Domain\Errors\Essentials\Unauthorized;

final class JsonResponse
{
    public static function successResponse(): LaravelJsonResponse
    {
        return new LaravelJsonResponse([
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public static function successResponseWithContent(array $content = []): LaravelJsonResponse
    {
        return new LaravelJsonResponse(array_merge([
            'status' => Response::HTTP_OK,
        ], $content), Response::HTTP_OK);
    }

    public static function build(\Throwable $e): string
    {
        $error = match (true) {
            $e instanceof BadRequest     => ApiErrors::badRequest($e),
            $e instanceof Unauthorized   => ApiErrors::unauthorized($e),
            $e instanceof Forbidden      => ApiErrors::forbidden($e),
            $e instanceof EntityNotFound => ApiErrors::EntityNotFound($e),
            default                      => ApiErrors::internalServer($e),
        };

        return json_encode([
            'status'  => $error['code'],
            'error'   => $error['message'],
            'details' => $e->getMessage(),
        ]);
    }
}
