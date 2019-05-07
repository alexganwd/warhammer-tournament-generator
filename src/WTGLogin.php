<?php

declare(strict_types=1);

namespace WTG;

use Psr\Http\Message\ResponseInterface;


class WTGLogin
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

        $credentials = $this->retrieve_credentials($_POST['email'],$_POST['password']);
        if ($credentials['password'] == md5($_POST['password'])) {
            $this->login($_POST['email']);
            $response->getBody()->write($_SESSION['email']);
        }
        else
            $response->getBody()->write("KO");

        return $response;
    }

    private function retrieve_credentials($email, $password) {
        return $this->database->fetch_query('SELECT * FROM ' . $this->table . ' WHERE email="'.$email. '" AND password="'. md5($password) . '"' );
    }

    private function login($email) {
        if(!isset($_SESSION['e-mail'])) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['restricted_access'] = True;
        }
    }

}