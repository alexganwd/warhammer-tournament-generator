<?php

declare(strict_types=1);

namespace WTG;

use Psr\Http\Message\ResponseInterface;


class WTGLogout
{
    private $response;



    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->response->withHeader('Content-Type', 'text/html');

        $this->logout();
        $response->getBody()->write("OK");
        return $response;
    }



    private function logout() {
        if(isset($_SESSION['e-mail']))
            $_SESSION = array();
            session_destroy();
    }

}