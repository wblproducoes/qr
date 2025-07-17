<?php

namespace App\Models;

use App\Core\Session;

class VisitorSession
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function getIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}
