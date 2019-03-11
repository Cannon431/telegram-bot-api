<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Interface TypeInterface
 *
 * Implements of this interface can return built params for request
 *
 * @package Justify\TelegramBotApi\Types
 */
interface TypeInterface
{
    /**
     * Builds params
     *
     * @return mixed params
     */
    public function getParams();
}
