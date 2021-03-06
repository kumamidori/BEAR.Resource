<?php

declare(strict_types=1);
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Resource\Exception;

use BEAR\Resource\Code;

class ServerErrorException extends \ErrorException implements ExceptionInterface
{
    public function __construct(string $message = '', $code = Code::ERROR, \Exception $previous = null)
    {
        parent::__construct($message, $code, 1, '', 0, $previous);
    }
}
