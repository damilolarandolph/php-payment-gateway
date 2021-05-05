<?php




class OauthController
{

    public function authorize()
    {
        require_once __DIR__ . "/../middleware/oauth-auth-stage-1.php";
        echo json_encode(array('message' => 'succcess'));
    }
}
