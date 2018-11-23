<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Represents a video to be sent
 * 
 * @link https://core.telegram.org/bots/api#inputmediavideo
 */
class InputMediaVideo extends InputMedia
{
    /**
     * Determines type of input media
     */
    const TYPE = 'video';

    /**
     * Builds params for request
     * 
     * @return array params
     */
    public function getParams()
    {
        return array_merge(['type' => self::TYPE, 'media' => $this->media], $this->optional);
    }
}
