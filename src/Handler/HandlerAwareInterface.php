<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger\Handler;

use Phoole\Logger\Entry\LogEntryInterface;

/**
 * HandlerAwareInterface
 *
 * @package Phoole\Logger
 */
interface HandlerAwareInterface
{
    /**
     * Add a handler
     *
     * @param  string        $level       level to handle
     * @param  callable      $handler
     * @param  string|object $entryClass  the log entry class/interface to handle
     * @param  int           $priority    handling priority
     * @return $this
     * @throws \InvalidArgumentException  if entry class or handler not right
     */
    public function addHandler(
        string $level,
        callable $handler,
        $entryClass = LogEntryInterface::class,
        int $priority = 50
    );
}