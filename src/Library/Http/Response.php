<?php
declare(strict_types = 1);

namespace App\Library\Http;

use Symfony\Component\HttpFoundation;

class Response extends HttpFoundation\Response implements ResponseInterface
{

    public function __construct($data = null, int $status = 200, array $headers = array(), bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }

    public static function createHttpNotAcceptable(): Response
    {
        return new static('You need to accept the "application/json" format in order to use this API',
                        Response::HTTP_NOT_ACCEPTABLE);
    }
}