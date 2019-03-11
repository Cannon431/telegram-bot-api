<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Abstract class denoting keyboards
 */
abstract class ReplyMarkup implements TypeInterface
{
    /**
     * Builds params
     *
     * @return mixed params
     */
    abstract public function getParams();
}
