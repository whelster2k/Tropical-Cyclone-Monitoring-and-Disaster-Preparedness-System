<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\HazardZone;

class WeatherServer implements MessageComponentInterface {
    protected $clients;
    private $updateInterval = 0.5; // Update interval in seconds

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        
        // Send initial weather data
        $this->sendWeatherUpdate($conn);
        
        // Start sending updates to this client
        $this->startUpdates($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        
        if ($data['type'] === 'subscribe' && $data['channel'] === 'weather_updates') {
            // Client is requesting weather updates
            $this->sendWeatherUpdate($from);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function sendWeatherUpdate(ConnectionInterface $conn) {
        // Get current weather data with random variations for Mindanao region
        $weather = [
            'rainfall' => max(0, min(150, 52 + (mt_rand(-10, 10) / 5))),
            'wind_speed' => max(0, min(200, 87 + (mt_rand(-15, 15) / 5))),
            'temperature' => max(20, min(40, 30 + (mt_rand(-10, 10) / 10))),
            'humidity' => max(0, min(100, 70 + (mt_rand(-10, 10) / 5))),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Send the update
        $conn->send(json_encode([
            'type' => 'weather_update',
            'weather' => $weather
        ]));
    }

    protected function startUpdates(ConnectionInterface $conn) {
        // Set up a timer to send updates
        $loop = $conn->getLoop();
        $loop->addPeriodicTimer($this->updateInterval, function() use ($conn) {
            $this->sendWeatherUpdate($conn);
        });
    }
} 