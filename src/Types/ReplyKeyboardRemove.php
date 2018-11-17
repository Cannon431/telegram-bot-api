<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Telegram clients will remove the current custom keyboard and display the default letter-keyboard
 * 
 * @link https://core.telegram.org/bots/api#replykeyboardremove
 */
class ReplyKeyboardRemove extends ReplyMarkup
{
    /**
     * @var array $optional optional params
     */
    private $optional;

    /**
     * Class constructor
     * 
     * @param array $optional array of optional params
     */
    public function __construct(array $optional = [])
    {
        $this->optional = $optional;
    }

    /**
     * Builds params for request
     * 
     * @return string params
     */
    public function getParams()
    {
        return json_encode(array_merge($this->optional, ['remove_keyboard' => true]));
    }
}
