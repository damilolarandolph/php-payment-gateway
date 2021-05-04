<?php
require_once __DIR__ . "/../../../common/rest/controller.php";
require_once __DIR__ . "/../data/repositories/consumer-repository.php";

class AddConsumerController extends \Gateway\REST\Controller
{

    /** @var ConsumerRepository */
    private $consumerRepo;
    public function __construct()
    {
        $this->consumerRepo = new ConsumerRepository();
    }
    public function get()
    {
        require_once __DIR__ . "/../views/add-consumer.php";
    }
    public function post()
    {
        $consumer = new Consumer();
        $consumer->bankAccount = $_POST['accountNumber'];
        $consumer->bankBIC = $_POST['bankBIC'];
        $consumer->apiSecret = bin2hex(random_bytes(32));
        $this->consumerRepo->save($consumer);
        header("Location: /consumers/add");
    }
}
