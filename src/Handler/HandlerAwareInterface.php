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

use Traversable;
use LogicException;
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
     * @param  HandlerInterface $handler
     * @param  string           $level       level to handle
     * @param  string           $entryClass  the log entry class/interface to handle
     * @param  int              $priority    handling priority
     * @return $this
     * @throws LogicException              if entry class unknown etc.
     */
    public function addHandler(
        HandlerInterface $handler,
        string $level,
        string $entryClass = LogEntryInterface::class,
        int $priority = 50
    );

    /**
     * Get handlers handling $level and $entry type
     *
     * @param  LogEntryInterface $entry
     * @return Traversable
     */
    public function getHandlers(LogEntryInterface $entry): Traversable;
}