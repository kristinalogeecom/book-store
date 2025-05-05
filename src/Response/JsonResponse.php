<?php

namespace BookStore\Response;

class JsonResponse extends Response
{
    /**
     * @param mixed $data
     * @param int $status_code
     * @param array $headers
     */
    public function __construct(mixed $data = [], int $status_code = 200, array $headers = [])
    {
        $body = json_encode($data);
        $headers['Content-Type'] = 'application/json';
        parent::__construct($status_code, $headers, $body);
    }

    /**
     * Creates a JSON response from the given data.
     *
     * @param mixed $data - Array or any serializable data
     * @param int $status_code
     * @return JsonResponse
     */
    public static function json(mixed $data = [], int $status_code = 200): JsonResponse
    {
        return new self($data, $status_code);
    }
}
