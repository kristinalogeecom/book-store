<?php

namespace BookStore\Response;


abstract class Response
{
    protected int $statusCode;
    protected array $headers;

    protected array $body;

    /**
     * @param int $statusCode
     * @param array $headers
     * @param array $body
     */
    public function __construct(int $statusCode = 200, array $headers = [], array $body = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
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
    /**
     * Sends the response headers and body to the client.
     *
     * @return void
     */
    abstract public function send(): void;

}