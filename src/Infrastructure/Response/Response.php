<?php

namespace BookStore\Infrastructure\Response;


abstract class Response
{
    protected int $statusCode;
    protected array $headers;

    /**
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(int $statusCode = 200, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function send(): void
    {
        $this->setCode();
        $this->setHeaders();
    }

    protected function setCode(): void
    {
        http_response_code($this->statusCode);
    }

    protected function setHeaders(): void
    {
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
    }
}