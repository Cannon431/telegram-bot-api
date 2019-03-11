<?php

namespace Justify\TelegramBotApi\Types;

/**
 * Abstract class denoting keyboards
 */
abstract class ReplyMarkup
{
    /**
     * Builds params
     *
     * @return mixed params
     */
    abstract public function getParams();
}
