<?php

namespace Framework\Cli;

use Dotenv\Dotenv;
use Framework\Utils\Colors;

class Server
{
    public static function start(): void
    {
        Dotenv::createImmutable(__DIR__ . '/../..')->load();

        $host = $_ENV['SERVER_HOST'];
        $port = $_ENV['SERVER_PORT'];

        $command = 'php -S ' . $host . ':' . $port . ' -t public';

        Colors::formatPrintLn(['yellow', 'bold'], '🚀 Starting server on ' . $host . ':' . $port . '...');

        shell_exec($command);
    }
}
