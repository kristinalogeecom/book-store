<?php

namespace BookStore\Infrastructure\Response;

class HtmlResponse extends Response
{
    protected string $template;
    protected array $params = [];

    /**
     * @param string $template
     * @param array $params
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(string $template, array $params = [], int $statusCode = 200, array $headers = [])
    {
        $this->template = $template;
        $this->params = $params;
        parent::__construct($statusCode, $headers);

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html';
        }
    }

    public function send(): void
    {
        $this->setCode();
        $this->setHeaders();

        ob_start();
        extract($this->params);
        include __DIR__ . '/../../Application/Presentation/Pages/' . $this->template . '.phtml';
        $content = ob_get_clean();

        echo $content;
    }
}