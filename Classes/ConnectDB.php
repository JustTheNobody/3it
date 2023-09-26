<?php

namespace Classes;

use PDO;
use PDOException;

class ConnectDB
{
    protected string $host;
    protected string $user;
    protected string $password;

    public function __construct($host, $user, $password)
    {
        $this->host     = $host;
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * @return PDO
     */
    public function makeConnection()
    {
        try {
            $conn = new PDO("mysql:host=$this->host;dbname=3it", $this->user, $this->password);
            // set the PDO error
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch(PDOException $e) {
            return [ "fail", "Connection failed: " . $e->getMessage()];
        }
    }
}