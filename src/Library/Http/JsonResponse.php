<?php
declare(strict_types = 1);

namespace App\Library\Http;

use Symfony\Component\HttpFoundation;

class JsonResponse extends HttpFoundation\JsonResponse implements ResponseInterface
{

    public function __construct($data = null, int $status = 200, array $headers = array(), bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }

    public static function createHal($data = null, int $status = 200, array $headers = array(), bool $json = false): JsonResponse
    {
        $halJsonResponse = new static($data, $status, $headers, $json);
        $halJsonResponse->headers->set('Content-Type', 'application/hal+json');
        
        return $halJsonResponse;
    }

    public static function createHttpBadRequest(array $errors): JsonResponse
    {
        return new static(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
    }

    public static function createHttpInternalServerError(): JsonResponse
    {
        return new static(['errors' => ['The server encountered an error, please try again later']],
                        JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function createHttpCreated(string $location): JsonResponse
    {
        return new static(null, JsonResponse::HTTP_CREATED, ['Location' => $location]);
    }

    public static function createHttpNotFound(string $error): JsonResponse
    {
        return new static(['errors' => [$error]], JsonResponse::HTTP_NOT_FOUND);
    }
}