<?php

namespace BookStore\Response;

class RedirectResponse extends Response
{
    /**
     * @param string $location
     * @param int $statusCode
     */
    public function __construct(string $location, int $statusCode = 302)
    {
        parent::__construct($statusCode, ['Location' => $location], []);
    }

    /**
     * Performs an HTTP redirect to the specified location.
     *
     * @param string $location
     * @return RedirectResponse
     */
    public static function to(string $location): RedirectResponse
    {
        return new self($location);
    }

    public function send(): void
    {
        $this->setCode();
        $this->setHeaders();
    }
}