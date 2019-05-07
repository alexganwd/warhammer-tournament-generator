<?php

declare(strict_types=1);

namespace WTG;
use PDO;
use PDOException;

class WTGPdo extends PDO {

    public function __construct($dataSource, $user, $pass) {
        $default_options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        parent::__construct($dataSource, $user, $pass, $default_options);
    }

    public function run_query($sql, $args = NULL) {
        try {
            $statement = $this->prepare($sql);
            $statement->execute($args);
            return "OK";

        }
        catch (PDOException $e) {
            return "KO";

        }

    }
    public function fetch_query($sql, $args = NULL) {
        try {
            $statement = $this->prepare($sql);
            $statement->execute($args);
            return $statement->fetch(PDO::FETCH_ASSOC);

        }
        catch (PDOException $e) {
            return "KO";

        }

    }

}