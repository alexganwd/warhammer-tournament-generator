<?php

declare(strict_types=1);

namespace WTG;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;


class WTGAdmin
{
    private $response;


    public function __construct(Environment $twig, ResponseInterface $response
    )
    {
        $this->twig = $twig;
        $this->response = $response;

    }
    public function __invoke(): ResponseInterface
    {
        $response = $this->response->withHeader('Content-Type', 'text/html');
        if (isset ($_SESSION['restricted_access']) AND isset ($_SESSION['email'])) {
            if ($_SESSION['restricted_access']) {
                $template = $this->twig->load('admin.html');
                $response->getBody()->write($template->render());
            }
        }
        else {
            $template = $this->twig->load('notallowed.html');
            $response->getBody()->write($template->render());
        }
        return $response;
    }

}