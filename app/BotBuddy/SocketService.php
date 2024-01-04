<?php

namespace App\BotBuddy;

class SocketService
{
    /**
     * Sends a message to the master server in HEADER\rMESSAGE format, where
     * HEADER is the header of the command and MESSAGE is the JSON encoded context.
     *
     * Returns true if the message was acknowledged by the master server.
     */
    public function send(string $header, array $data): bool
    {
        $jsonData = sprintf("%s\r%s", $header, json_encode($data));

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            return false;
        }

        $result = socket_connect($socket, env('RS_MASTER_HOST'), env('RS_MASTER_PORT'));
        if (!$result) {
            return false;
        }

        $bytes = socket_write($socket, $jsonData, strlen($jsonData));
        if ($bytes === false) {
            return false;
        }

        $response = socket_read($socket, 2048);
        if (!$response) {
            return false;
        }

        socket_close($socket);

        return (bool) $response;
    }
}
