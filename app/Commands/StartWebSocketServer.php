<?php

namespace App\Commands;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\WeatherServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;

class StartWebSocketServer {
    public function execute() {
        $loop = Factory::create();
        $webSocket = new WsServer(new WeatherServer());
        
        // Create a secure WebSocket server
        $secureWebSocket = new HttpServer($webSocket);
        
        // Create a socket for our server
        $socket = new Server('0.0.0.0:8080', $loop);
        
        // Wrap the socket in a secure layer
        $secureSocket = new SecureServer($socket, $loop, [
            'local_cert' => '/path/to/your/certificate.pem',
            'local_pk' => '/path/to/your/private.key',
            'allow_self_signed' => true,
            'verify_peer' => false
        ]);
        
        // Create the server
        $server = new IoServer($secureWebSocket, $secureSocket, $loop);
        
        echo "WebSocket server started on wss://0.0.0.0:8080\n";
        
        $loop->run();
    }
} 