<?php

namespace BookStore\Response;

class HtmlResponse extends Response
{
    /**
     * @param string $body
     * @param int $status_code
     * @param array $headers
     */
    public function __construct(string $body = '', int $status_code = 200, array $headers = [])
    {
        parent::__construct($status_code, $headers, $body);
        $this->headers['Content-Type'] = 'text/html';
    }

    /**
     * Renders the HTML view and returns the response.
     *
     * @param string $template
     * @param array $params
     * @return HtmlResponse
     */
    public static function view(string $template, array $params = []): HtmlResponse
    {
        extract($params);
        ob_start();
        include __DIR__ . '/../../public/pages/' .$template . '.phtml';
        $html = ob_get_clean();

        return new self($html);
    }
}