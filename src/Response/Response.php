<?php

namespace BookStore\Response;

use BookStore\Service\AuthorService;

abstract class Response
{
    protected int $statusCode;
    protected array $headers;

    protected string $body;

    /**
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     */
    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Sends the response headers and body to the client.
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }

}