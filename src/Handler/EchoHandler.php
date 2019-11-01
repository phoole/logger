<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types = 1);

namespace Phoole\Logger\Handler;

use Phoole\Logger\Entry\LogEntryInterface;

/**
 * echo log message to STDOUT
 *
 * @package Phoole\Logger
 */
class EchoHandler extends HandlerAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $entry)
    {
        echo $this->getFormatter()->format($entry);
    }
}
