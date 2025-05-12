<?php

namespace BookStore\Application\Presentation\Controller;

use BookStore\Infrastructure\Response\HtmlResponse;

class ErrorController
{
    public function render(string $message = 'Unknown error occurred.'): HtmlResponse
    {
        return new HtmlResponse('error', ['message' => $message]);
    }
}