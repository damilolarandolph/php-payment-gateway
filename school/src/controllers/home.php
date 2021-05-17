<?php


class HomeController
{

    public function home()
    {
        header("Content-Type: text/html");
        require_once __DIR__ . "/../../frontend/organizer-dashboard.html";
    }
}
