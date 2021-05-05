<?php
require_once __DIR__ . "/../data/repositories/consumer-repository.php";

class AddConsumerController
{

    /** @var ConsumerRepository */
    private $consumerRepo;
    public function __construct()
    {
        $this->consumerRepo = new ConsumerRepository();
    }
    public function show()
    {
        require_once __DIR__ . "/../views/add-consumer.php";
    }
    public function create()
    {
        $consumer = new Consumer();
        $consumer->bankAccount = $_POST['accountNumber'];
        $consumer->bankBIC = $_POST['bankBIC'];
        $consumer->apiSecret = bin2hex(random_bytes(32));
        $this->consumerRepo->save($consumer);
        header("Location: /consumers/add");
    }
}
