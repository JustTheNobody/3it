<?php

namespace Controllers;

use PDO;
use Models\Data;
use Models\Import;
use Models\FlashMessageTrait;

class HandleController
{
    use FlashMessageTrait;

    private PDO $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function handleRequest()
    {
        $view = isset($_GET['view']) ? $_GET['view'] : 'home';

        switch ($view) {
            case 'home':
                require 'views/home.php';
                break;
            case 'data':
                $result = $this->viewData();
                $flash = self::getFlash();
                require 'views/data.php';
                break;
            default:
                echo "404 Not Found";
        }
    }

    public function viewData()
    {
        $dbData = new Data($this->connect);

        if (isset($_GET['delete']))
            $dbData->deleteData();

        if (isset($_GET['import'])) {
            $import = new Import($this->connect);
            $import->saveToDb();
        }

        return $dbData->getData();
    }
}
