<?php


namespace Test;
define('FILE_ENCRYPTION_BLOCKS', 10000);
include "start.php";
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;
use Amp\Loop;

require __DIR__ . '/../vendor/autoload.php';


class MyWebSocketServer implements MessageComponentInterface {

    protected $clients;
    public function __construct($MadelineProto) {
        echo "Server is starting\n";
        $this->madeline = $MadelineProto;
        $this->clients = new \SplObjectStorage;
    }
    public function onOpen(ConnectionInterface $conn) {
        echo "Express proxy connected\n";
        $this->clients->attach($conn);
        $conn->send(json_encode(["ok"=>true]));
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        $se = json_decode($msg,true);
        $comm = $se["command"]??"";
	    $file = $se["file"]??[];
	    $body = $se["body"]??[];
        $get  = $se["get"]??[];
        $params  = $se["paramz"]??[];
        $isbot = $se["isbot"]??false;
        $MadelineProto = $this->madeline;
        switch ($comm) {
            case "upload": {
              include "upload.php";
              break;
            }
            case "search": {
              include "search.php";
              break;
            }
            case "fileh": {
              include "fileh.php";
              break;
            }
            case "fnr": {
              include "rawfileh.php";
              break;
            }
            case "del": {
              include "DeleteFile.php";
              break;
            }
            default: {
                break;
            }
        }

    }
    public function onClose(ConnectionInterface $conn) {
        echo "Express proxy disconnected\n";
        $this->clients->detach($conn);
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        // ... //
    }
  }
    $server = IoServer::factory(
      new HttpServer(
          new WsServer(
              new MyWebSocketServer($MadelineProto)
          )
      ),
      5433 // porta
  );
  $server->run();
