<?php

use Gateway\Routing\Router;

require __DIR__ . "/../common/routing/router.php";
require_once __DIR__ . "/../common/rest/controller.php";
require_once __DIR__ . "/src/controllers/oauth/oauth-authorization.php";
header("Content-Type: application/json");

$router = new Router();


$router->add("/migrate", new class extends \Gateway\REST\Controller
{
    public function get()
    {
        require_once __DIR__ . "/src/data/config/migrate.php";
    }
});

$router->add("/", new class extends \Gateway\REST\Controller
{

    public function get()
    {
        var_dump(openssl_pkey_get_details(openssl_pkey_new())['key']);
    }
});

$router->add("/oauth/authorize", new OauthAuthorizationController());


$router->navigate();
