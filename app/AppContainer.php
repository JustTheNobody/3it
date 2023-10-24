<?php

namespace app;

use Exception;

class AppContainer {

    private $services = [];

    public function __construct()
    {
        $this->services['app'] = (object) require 'config/app.php';
        $this->services['db']  = (object) require 'config/db.php';
    }

    public function get($service)
    {
        if (isset($this->services[$service])) {
            return $this->services[$service];
        } else {
            throw new Exception("Service '$service' not found in the container.");
        }
    }
}