<?php
require_once __DIR__ . "/../../../common/rest/controller.php";

class AddConsumerController extends \Gateway\REST\Controller
{
    public function get()
    {
        require_once __DIR__ . "/../views/add-consumer.php";
    }
    public function post()
    {
        var_dump($_POST);
    }
}
