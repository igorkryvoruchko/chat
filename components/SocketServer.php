<?php
namespace app\components;
use app\models\Message;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
class SocketServer implements MessageComponentInterface
{
    protected $clients;
    private $subscriptions;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $data = json_decode($msg);
        echo var_dump($msg);
        switch ($data->command) {
            case "subscribe":
                $this->subscriptions[$data->channel] = $conn->resourceId;
                echo "sibscribe " . $conn->resourceId.": " .$data->channel."/n";
                break;
            case "message":
                (new Message())->saveMessage($data);
                if (isset($this->subscriptions[$data->to])) {
                    $target = $this->subscriptions[$data->to];
                    foreach ($this->clients as $client) {
                        if ($client->resourceId == $target) {
                            $client->send($msg);
                        }
                    }
                }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->subscriptions[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}