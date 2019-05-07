<?php

declare(strict_types=1);

namespace WTG;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;


class WTGHome
{
    private $response;
    private $twig;


    public function __construct(Environment $twig, ResponseInterface $response
    )
    {
        $this->twig = $twig;
        $this->response = $response;

    }
    public function __invoke(): ResponseInterface
    {
        $template = $this->twig->load('home.html');
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write($template->render());
        return $response;

    }

}