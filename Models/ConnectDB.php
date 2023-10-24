<?php

namespace Models;

use PDO;
use Exception;
use PDOException;
use stdClass;

class ConnectDB
{
    /**
     * @return PDO
     */
    public function makeConnection($app)
    {
        try {
            $host   = $app->host;
            $dbname = $app->dbname;

            $conn = new PDO("mysql:host=$host; dbname=$dbname", $app->user, $app->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
}
