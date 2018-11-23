<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Represents a photo to be sent
 * 
 *  @link https://core.telegram.org/bots/api#inputmediaphoto
 */
class InputMediaPhoto extends InputMedia
{
    /**
     * Determines type of input media
     */
    const TYPE = 'photo';

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
