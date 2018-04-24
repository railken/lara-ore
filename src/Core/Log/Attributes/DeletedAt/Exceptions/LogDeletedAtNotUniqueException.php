<?php

namespace Railken\LaraOre\Core\Log\Attributes\DeletedAt\Exceptions;

use Railken\LaraOre\Core\Log\Exceptions\LogAttributeException;

class LogDeletedAtNotUniqueException extends LogAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'deleted_at';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'LOG_DELETED_AT_NOT_UNIQUE';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is not unique';
}
