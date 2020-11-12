<?php

namespace DiscordHandler;

use GuzzleHttp\Client;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\Request as Request;
use Auth;

class DiscordHandler extends AbstractProcessingHandler
{
    /**
     * @var \GuzzleHttp\Client;
     */
    private $client;

    /**
     * @var array
     */
    private $webhooks;

    /**
     * Colors for a given log level.
     *
     * @var array
     */
    protected $levelColors = [
        Logger::DEBUG => 10395294,
        Logger::INFO => 5025616,
        Logger::NOTICE => 6323595,
        Logger::WARNING => 16771899,
        Logger::ERROR => 16007990,
        Logger::CRITICAL => 16007990,
        Logger::ALERT => 16007990,
        Logger::EMERGENCY => 16007990,
    ];

    /**
     * DiscordHandler constructor.
     * @param $webhooks
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($webhooks, $level, $bubble = true)
    {
        $this->client = new Client();
        $this->webhooks = $webhooks;
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function write(array $record)
    {
        $content = [
            "embeds" => [
                [
                    "title" => '[' . date_format($record['datetime'], 'Y-m-d H:i:s') . '] ' . $record['level_name'] . ' on ' . Request::url(),
                    "description" => substr($record['message'], 0, 2048),
                    "color" => $this->levelColors[$record['level']],
                ],
            ],
        ];

        if (Auth::check()) {
            $content["embeds"][0]["footer"] = [
                "text" => 'USERID: ' . Auth::user()->id . ' | EMAIL: ' . Auth::user()->email,
                "icon_url" => 'https://www.popularitas.com/wp-content/uploads/2018/04/user-hero-blue.png'
            ];
        }

        foreach ($this->webhooks as $webhook) {
            $this->client->request('POST', $webhook, [
                'json' => $content,
            ]);
        }
    }
}
