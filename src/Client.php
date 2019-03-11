<?php

namespace Justify\TelegramBotApi;

/**
 * Client for handle incoming information from Telegram
 */
class Client
{
    /**
     * @var \Justify\TelegramBotApi\Api
     */
    public $api;

    /**
     * @var array array of events
     */
    private $events = [];

    /**
     * @var array array of handlers
     */
    private $handlers = [];

    /**
     * @var array array of checked updates
     */
    private $checked = [];

    /**
     * Class constructor
     *
     * @param string $token Telegram bot API token
     */
    public function __construct($token)
    {
        $this->api = new Api($token);
    }

    /**
     * Runs long polling
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @param integer $timeout timeout between getting update
     */
    public function polling($timeout = 1)
    {
        while (true) {
            $updates = $this->api->getUpdates();

            foreach ($updates->result as $update) {
                if (in_array($update->update_id, $this->checked)) {
                    continue;
                }

                $this->runHandlers($update);
                $this->checkEvents($update);
            }

            sleep($timeout);
        }
    }

    /**
     * Binds $handler on $command
     *
     * @param string $command command (text to which adds slash)
     * @param \Closure $handler handler triggers on $command
     */
    public function command($command, \Closure $handler)
    {
        if ($command[0] !== '/') {
            $command = '/' . $command;
        }

        $this->events[] = [
            'type' => 'text',
            'text' => $command,
            'handler' => $handler
        ];
    }

    /**
     * Binds $handler on $text
     *
     * @param string $text text
     * @param \Closure $handler handler triggers on $text
     */
    public function text($text, \Closure $handler)
    {
        $this->events[] = [
            'type' => 'text',
            'text' => $text,
            'handler' => $handler
        ];
    }

    /**
     * Binds $handler on $regexp
     *
     * @param string $regexp regular expression
     * @param \Closure $handler handler triggers on coincidence with $regexp
     */
    public function regexp($regexp, \Closure $handler)
    {
        $this->events[] = [
            'type' => 'regexp',
            'regexp' => $regexp,
            'handler' => $handler
        ];
    }

    /**
     * Binds $handler on $type
     *
     * @param string $type type of message
     * @param \Closure $handler handler triggers if message have $type
     */
    public function type($type, \Closure $handler)
    {
        $this->events[] = [
            'type' => 'type',
            '_type' => $type,
            'handler' => $handler
        ];
    }

    /**
     * Handler which triggers on received update
     *
     * @param \Closure $handler
     */
    public function handler(\Closure $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Checks and triggers events
     *
     * @param object $update
     */
    private function checkEvents($update)
    {
        $message = $update->message;

        foreach ($this->events as $event) {
            if ($this->isText($message, $event) || $this->isType($event)) {
                if ($this->isRegexp($message, $event, $m)) {
                    $event['handler']($message, $m);
                } else {
                    $event['handler']($message);
                }
            }
        }

        $this->checked[] = $update->update_id;
    }

    /**
     * Checks if type of incoming update is "text"
     *
     * @param object $message message object
     * @param array $event event
     * @return bool
     */
    private function isText($message, array $event)
    {
        return isset($message->text) && $message->text === $event['text'];
    }

    /**
     * Checks if type of incoming update is "regexp"
     *
     * @param object $message message object
     * @param array $event event
     * @param array $m will record result from preg_match_all function
     * @return bool
     */
    private function isRegexp($message, $event, &$m)
    {
        return isset($message->text) && isset($even['regexp']) &&
            preg_match_all('#' . $event['regexp'] . '#u', $message->text, $m);
    }

    /**
     * Checks if type of incoming update is "type"
     *
     * @param array $event event
     * @return bool
     */
    private function isType($event)
    {
        return isset($message->$event['_type']);
    }

    /**
     * Runs handlers
     *
     * @param object $update
     */
    private function runHandlers($update)
    {
        foreach ($this->handlers as $handler) {
            $handler($update->message);
        }

        $this->checked[] = $update->update_id;
    }

    /**
     * Allows call methods from $this->api object
     *
     * @param string $name method name
     * @param array $args arguments of $this->api->$name method
     *
     * @throws \Justify\TelegramBotApi\Exception
     * @return mixed
     */
    public function __call($name, array $args = [])
    {
        if (method_exists($this->api, $name)) {
            return call_user_func_array([$this->api, $name], $args);
        }

        throw new Exception("Method \"$name\" doesn't exist");
    }
}

