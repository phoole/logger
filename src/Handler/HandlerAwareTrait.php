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

use Psr\Log\LogLevel;
use Phoole\Logger\Entry\LogLevelTrait;
use Phoole\Logger\Entry\LogEntryInterface;
use Phoole\Base\Queue\UniquePriorityQueue;

/**
 * HandlerAwareTrait
 *
 * @package Phoole\Logger
 */
trait HandlerAwareTrait
{
    use LogLevelTrait;

    /**
     * queue for the handlers
     *
     * @var  HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * {@inheritDoc}
     */
    public function addHandler(
        HandlerInterface $handler,
        string $level,
        string $entryClass = LogEntryInterface::class,
        int $priority = 50
    ): HandlerAwareInterface {
        if (!isset($this->handlers[$level][$entryClass])) {
            $this->handlers[$level][$entryClass] = new UniquePriorityQueue();
        }
        $this->handlers[$level][$entryClass]->insert($handler, $priority);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHandlers(LogEntryInterface $entry): \Traversable
    {
        $queue = new UniquePriorityQueue();
        $level = $entry->getLevel();
        foreach ($this->handlers as $l => $qs) {
            // match level
            if (!$this->matchLevel($level, $l)) {
                continue;
            }

            // match class
            foreach ($qs as $class => $q) {
                if (is_a($entry, $class)) {
                    $queue = $queue->combine($q);
                }
            }
        }
        return $queue;
    }

    /**
     * return TRUE if can handle the entry level
     *
     * @param  string $entryLevel
     * @param  string $handlerLevel
     * @return bool
     */
    protected function matchLevel(string $entryLevel, string $handlerLevel): bool
    {
        if ($this->convert[$entryLevel] >= $this->convert[$handlerLevel]) {
            return true;
        }
        return false;
    }
}
