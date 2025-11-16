<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\infrastructure\Http\Api\Services;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use VendingMachine\Shared\Domain\Errors\Essentials\BadRequest;
use VendingMachine\Shared\Domain\Errors\Essentials\EntityNotFound;
use VendingMachine\Shared\Domain\Errors\Essentials\Forbidden;
use VendingMachine\Shared\Domain\Errors\Essentials\Unauthorized;

final class ApiErrors
{
    public static function badRequest(BadRequest $badRequest): array
    {
        return [
            'code'    => Response::HTTP_BAD_REQUEST,
            'message' => $badRequest->getMessage(),
        ];
    }

    public static function unauthorized(Unauthorized $unauthorized): array
    {
        return [
            'code'    => Response::HTTP_UNAUTHORIZED,
            'message' => $unauthorized->getMessage(),
        ];
    }

    public static function forbidden(Forbidden $forbidden): array
    {
        return [
            'code'    => Response::HTTP_FORBIDDEN,
            'message' => $forbidden->getMessage(),
        ];
    }

    public static function entityNotFound(EntityNotFound $entityNotFound): array
    {
        return [
            'code'    => Response::HTTP_NOT_FOUND,
            'message' => $entityNotFound->getMessage(),
        ];
    }

    public static function internalServer(Throwable $internalServer): array
    {
        return [
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $internalServer->getMessage(),
        ];
    }
}
