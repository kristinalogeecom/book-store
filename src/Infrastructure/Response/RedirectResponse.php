<?php

namespace BookStore\Infrastructure\Response;

class RedirectResponse extends Response
{
    /**
     * @param string $location
     * @param int $statusCode
     */
    public function __construct(string $location, int $statusCode = 302)
    {
        parent::__construct($statusCode, ['Location' => $location]);
    }
}