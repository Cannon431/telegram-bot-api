<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Represents a custom keyboard with reply options
 *
 * @link https://core.telegram.org/bots/api#replykeyboardmarkup
 */
class ReplyKeyboardMarkup extends ReplyMarkup
{
    /**
     * @var array $buttons array of buttons
     */
    private $buttons;

    /**
     * @var array $optional array of optional params
     */
    private $optional;

    /**
     * Class constructor
     *
     * @param array $optional optional params
     */
    public function __construct(array $optional = [])
    {
        $this->optional = $optional;
    }

    /**
     * Creates row of buttons
     *
     * There is a possibility to use chaining
     *
     * @param array $buttons array of buttons
     *
     * @return \Justify\TelegramBotApi\Types\ReplyKeyboardMarkup
     */
    public function row(...$buttons)
    {
        $this->buttons[] = $buttons;

        return $this;
    }

    /**
     * Builds params for request
     *
     * @return string params
     */
    public function getParams()
    {
        return json_encode(array_merge($this->optional, ['keyboard' => $this->buttons]));
    }
}
