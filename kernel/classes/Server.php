<?php
declare(strict_types=1);

namespace Kernel;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server as SwooleServer;
use Swoole\WebSocket\Server as WebSocketServer;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\CloseFrame;
use Swoole\Runtime;
use Swoole\Coroutine;

enum ServerStatus
{
    case STARTED = 'started';
    case STOPPED = 'stopped';
    case RESTARTED = 'restarted';

    static $value;

    public static function init(string $value): string
    {
        self::$value = strtolower($value);
        return self::$value;
    }

    public static function isStarted(): bool
    {
        return static::$value === self::STARTED;
    }
}

class Server
{
    use \Singleton;

    private static $instance;
    public $app;
    
    public $http;
    public $status;
    public $webSocket;
    public $gprc;

    private function __construct($serverStatus = 'started')
    {
        if (self::$instance) {
            return self::$instance;
        }
        
        self::$status = ServerStatus::init(ServerStatus::STARTED);
        // Create a new Swoole HTTP server
        $this->http = new SwooleServer('0.0.0.0', 8000);

    }

    public static function startWebSocketServer($serverArgs = [], $listener = [])
    {
        $websocketDefaultListener = [
            'host' => $listener['host'] ?? '0.0.0.0',
            'port' => $listener['port'] ?? 9512
        ];

        $websocketDefaultServer = [
            'host' => $serverArgs['host'] ?? '0.0.0.0',
            'port' => $serverArgs['port'] ?? 9502
        ];

        $server = self::getInstance();
        $server->webSocket = new WebSocketServer();
        $server->webSocket->set($serverArgs);
        $server->webSocket->listen(host: $websocketDefaultListener['host'], port: $websocketDefaultListener['port']);
    }

    public static function startGRPCServer($serverArgs = [], $listener = [])
    {
        $grpcDefaultListener = [
            'host' => $listener['host'] ?? '127.0.0.1',
            'port' => $listener['port'] ?? 9503,
        ];

        $grpcDefaultServer = [
            'host' => $serverArgs['host'] ?? '0.0.0.0',
            'port' => $serverArgs['port'] ?? 9513,
        ];

        $server = self::getInstance();
        
    }


    public function start($workerNum = 4, $daemonize = false, $maxRequest = 10000, $dispatchMode = 2, $logFile = '/var/log/swoole.log')
    {
        $this->http = new SwooleServer();
        $this->http->set([
            'worker_num' => $workerNum,
            'daemonize' => $daemonize,
            'max_request' => $maxRequest,
            'dispatch_mode' => $dispatchMode,
            'log_file' => $logFile,
        ]);
        
        $this->http->start();
    }

    public function startWebSocket($workerNum = 4, $daemonize = false, $maxRequest = 10000, $dispatchMode = 2, $logFile = '/var/log/swoole.log')
    {
        $this->webSocket = new WebSocketServer();
        $this->webSocket->set([
            'worker_num' => $workerNum,
            'daemonize' => $daemonize,
            'max_request' => $maxRequest,
            'dispatch_mode' => $dispatchMode,
            'log_file' => $logFile,
        ]);

        $this->webSocket->on('open', function (WebSocketServer $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $this->webSocket->on('message', function (WebSocketServer $server, Frame $frame) {
            echo "receive from {$frame->fd}:{$frame->data}, opcode:{$frame->opcode}, fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        $this->webSocket->on('close', function (WebSocketServer $server, $fd) {
            echo "client {$fd} closed\n";
        });

        $this->webSocket->start();
    }

    public function startProduction($workerNum = 4, $daemonize = true, $maxRequest = 10000, $dispatchMode = 2, $logFile = '/var/log/swoole.log')
    {
        $this->http = new SwooleServer();
        $this->http->set([
            'worker_num' => $workerNum,
            'daemonize' => $daemonize,
            'max_request' => $maxRequest,
            'dispatch_mode' => $dispatchMode,
            'log_file' => $logFile,
        ]);

        $this->http->start();
    }

    public function init()
    {

        $app = Application::getInstance();

        // Handle incoming requests
        $this->http->on('request', function (Request $request, Response $response) use ($app) {
            
            
            // Set request and response in the app instance
            $app->set('request', $request);
            $app->set('response', $response);

            // Handle the request
            ob_start();
            include PUBLIC_DIR . '/index.php';
            $content = ob_get_clean();

            // Send the response
            $response->end($content);
        });

        // Set the document root
        $this->http->set([
            'document_root' => PUBLIC_DIR,
            'enable_static_handler' => true,
        ]);

        // Start the server
        $this->http->start();


        // Handle incoming requests
        $this->http->on('request', function (Request $request, Response $response) {


            // Handle the request
            ob_start();
            include PUBLIC_DIR . '/index.php';
            $content = ob_get_clean();

            // Send the response
            $response->end($content);
        });
    }
}