<?php

namespace BookStore\Response;

abstract class Response
{
    protected int $status_code;
    protected array $headers;

    protected string $body;

    /**
     * @param int $status_code
     * @param array $headers
     * @param string $body
     */
    public function __construct(int $status_code = 200, array $headers = [], string $body = '')
    {
        $this->status_code = $status_code;
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
        http_response_code($this->status_code);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }
}