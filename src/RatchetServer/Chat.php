<?php
namespace App\RatchetServer;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Vangrg\ProfanityBundle\Service\ProfanityChecker;
use Vangrg\ProfanityBundle\Storage\ProfanitiesStorageDefault;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        $storage= new ProfanitiesStorageDefault("C:\Dev\Wamp64\www\Infernal_Games_Project-master\public\profanities.json", "json");
        $checker= new ProfanityChecker($storage, false);
        $text= $checker->obfuscateIfProfane($data['text']);
        $data['text']= $text;


        foreach ($this->clients as $client) {
            //if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                //$client->send($msg);
                $client->send(json_encode($data));
            //}
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}