<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\infrastructure\Http\Api\Services;

use Symfony\Component\HttpFoundation\Response;
use VendingMachine\Shared\Domain\Errors\BadRequest;
use VendingMachine\Shared\Domain\Errors\EntityNotFound;
use VendingMachine\Shared\Domain\Errors\Forbidden;
use VendingMachine\Shared\Domain\Errors\Unauthorized;

final class JsonResponse
{
    public static function successResponseWithData(array $data = []): string
    {
        return json_encode([
            'status' => Response::HTTP_OK,
            'data'   => $data,
        ]);
    }

    public static function successResponse(): string
    {
        return json_encode([
            'status' => Response::HTTP_OK,
        ]);
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
