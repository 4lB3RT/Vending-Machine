<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\infrastructure\Http\Api\Services;

use Symfony\Component\HttpFoundation\Response;

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
}
