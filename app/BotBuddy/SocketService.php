<?php

namespace App\BotBuddy;

class SocketService
{
    /**
     * Sends a message to the master server in HEADER\rMESSAGE format, where
     * HEADER is the header of the command and MESSAGE is the JSON encoded context.
     *
     * Returns a string if the message was acknowledged by the master server,
     * otherwise returns an empty string.
     */
    public function send(string $header, array $data): string
    {
        $jsonData = sprintf("%s\r%s", $header, json_encode($data));

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            return "";
        }

        $result = socket_connect($socket, env('RS_MASTER_HOST'), env('RS_MASTER_PORT'));
        if (!$result) {
            return "";
        }

        $bytes = socket_write($socket, $jsonData, strlen($jsonData));
        if ($bytes === false) {
            return "";
        }

        $response = "";

        while ($out = socket_read($socket, 2048)) {
            $response .= $out;
        }

        if ($response == "") {
            return "";
        }

        socket_close($socket);

        return $response;
    }
}
