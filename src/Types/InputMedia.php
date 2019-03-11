<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Represents the content of a media message to be sent
 *
 * @link https://core.telegram.org/bots/api#inputmedia
 */
abstract class InputMedia implements TypeInterface
{
    /**
     * @var string $media file to send
     *
     * Pass a file_id to send a file that exists on the Telegram servers
     */
    protected $media;

    /**
     * @var array $optional array of optional params
     */
    protected $optional;

    /**
     * Class constructor
     *
     * @param string $media pass a file_id to send a file that exists on the Telegram servers
     * @param array $optional array of optional params
     */
    public function __construct($media, array $optional = [])
    {
        $this->media = $media;
        $this->optional = $optional;
    }

    abstract public function getParams();
}
