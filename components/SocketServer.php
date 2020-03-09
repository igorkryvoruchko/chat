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
        $changeData = json_decode(json_encode($data), true);
        switch ($data->command) {
            case "subscribe":
                $this->subscriptions[$data->channel] = $conn->resourceId;
                echo "sibscribe " . $conn->resourceId.": " .$data->channel."/n";
                break;
            case "message":
                $result = (new Message())->saveMessage($data);
                $changeData['message_id'] = $result['message_id'];
                $changeData['time'] = $result['time'];
                $fullMessage = json_encode($changeData);
                echo var_dump($changeData);
                if (isset($this->subscriptions[$data->to])) {
                    $target = $this->subscriptions[$data->to];
                    foreach ($this->clients as $client) {
                        if ($client->resourceId == $target) {
                            $client->send($fullMessage);
                        }
                    }
                }
                if (isset($this->subscriptions[$data->userId])) {
                    $from = $this->subscriptions[$data->userId];
                    foreach ($this->clients as $client) {
                        if ($client->resourceId == $from) {
                            $client->send($fullMessage);
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