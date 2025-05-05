<?php

namespace BookStore\Response;

class RedirectResponse extends Response
{
    /**
     * @param string $location
     * @param int $status_code
     */
    public function __construct(string $location, int $status_code = 302)
    {
        parent::__construct($status_code, ['Location' => $location], '');
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
}