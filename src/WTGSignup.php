<?php

declare(strict_types=1);

namespace WTG;

use Psr\Http\Message\ResponseInterface;


class WTGSignup
{
    private $database;
    private $table = "users";
    private $response;



    public function __construct(WTGPdo $database, ResponseInterface $response)
    {
        $this->database = $database;
        $this->response = $response;
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write($this->signup($_POST['email'], $_POST['password']));
        return $response;

    }

    private function signup($email, $password) {
       return $this->database->run_query('INSERT INTO ' . $this->table . ' (email, password) VALUES (?,?)', array($email, md5($password)));
    }

}