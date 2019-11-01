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
 * HandlerInterface
 *
 * @package Phoole\Logger
 */
interface HandlerInterface
{
    /**
     * Handle the entry and return it
     *
     * @param  LogEntryInterface $entry
     * @return LogEntryInterface
     */
    public function handle(LogEntryInterface $entry): LogEntryInterface;
}
