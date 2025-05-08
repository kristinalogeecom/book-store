<?php

namespace BookStore\Response;

class JsonResponse extends Response
{
    /**
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(mixed $data = [], int $statusCode = 200, array $headers = [])
    {
        $headers['Content-Type'] = 'application/json';
        parent::__construct($statusCode, $headers, $data);
    }

    /**
     * Creates a JSON response from the given data.
     *
     * @param mixed $data - Array or any serializable data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function json(mixed $data = [], int $statusCode = 200): JsonResponse
    {
        return new self($data, $statusCode);
    }

    public function send(): void
    {
        $this->setCode();
        $this->setHeaders();

        echo json_encode($this->body);
    }
}
