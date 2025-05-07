<?php

namespace BookStore\Response;

class HtmlResponse extends Response
{
    protected array $params = [];


    /**
     * @param string $template
     * @param array $params
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(string $template, array $params = [], int $statusCode = 200, array $headers = [])
    {
        $this->params = $params;
        $this->headers = $headers;
        $this->headers['Content-Type'] = 'text/html';

        ob_start();
        extract($params);
        include __DIR__ . '/../../public/pages/' . $template . '.phtml';
        $html = ob_get_clean();

        parent::__construct($statusCode, $this->headers, $html);
    }

}