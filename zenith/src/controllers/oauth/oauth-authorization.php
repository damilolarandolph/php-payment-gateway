<?php

use Gateway\REST\Controller;

require_once __DIR__ . "/../../../../common/rest/controller.php";


class OauthAuthorizationController extends Controller
{

    public function post()
    {
        require_once __DIR__ . "/../../middleware/oauth-auth-stage-1.php";
        var_dump($consumer);
    }
}
