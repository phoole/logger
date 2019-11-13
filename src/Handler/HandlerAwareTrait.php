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

use Phoole\Logger\Entry\LogLevelTrait;
use Phoole\Base\Queue\UniquePriorityQueue;
use Phoole\Logger\Entry\LogEntryInterface;
use Phoole\Logger\Processor\VerifyCallableTrait;

/**
 * HandlerAwareTrait
 *
 * @package   Phoole\Logger
 * @interface HandlerAwareInterface
 */
trait HandlerAwareTrait
{
    use LogLevelTrait;
    use VerifyCallableTrait;

    /**
     * queue for the handlers
     *
     * @var  UniquePriorityQueue[][]
     */
    protected $handlers = [];

    /**
     * @var array
     */
    protected $handlerCache = [];

    /**
     * {@inheritDoc}
     */
    public function addHandler(
        string $level,
        callable $handler,
        $entryClass = LogEntryInterface::class,
        int $priority = 50
    ) {
        // verify parameters
        $requiredClass = self::verifyCallable($handler, LogEntryInterface::class);
        $entryClass = $this->checkEntry($entryClass, $requiredClass);

        // add handler
        $q = $this->handlers[$level][$entryClass] ?? new UniquePriorityQueue();
        $q->insert($handler, $priority);
        $this->handlers[$level][$entryClass] = $q;

        // clear cache
        $this->handlerCache = [];

        return $this;
    }

    /**
     * Get handlers handling $level and $entry type
     *
     * @param  LogEntryInterface $entry
     * @return \Traversable
     */
    protected function getHandlers(LogEntryInterface $entry): \Traversable
    {
        // check cache
        $level = $entry->getLevel();
        if (isset($this->handlerCache[$level][\get_class($entry)])) {
            return $this->handlerCache[$level][\get_class($entry)];
        }

        // find matching handlers
        $queue = $this->findHandlers($entry, $level);

        // update cache
        $this->handlerCache[$level][\get_class($entry)] = $queue;

        return $queue;
    }

    /**
     * @param  string|object $entryClass
     * @param  string        $requiredClass
     * @return string
     * @throws \InvalidArgumentException if not valid message entry
     */
    protected function checkEntry($entryClass, string $requiredClass): string
    {
        if (!\is_a($entryClass, $requiredClass, TRUE)) {
            throw new \InvalidArgumentException("not a valid entry " . $requiredClass);
        }
        return \is_string($entryClass) ? $entryClass : \get_class($entryClass);
    }

    /**
     * @param  LogEntryInterface $entry
     * @param  string            $level
     * @return UniquePriorityQueue
     */
    protected function findHandlers(LogEntryInterface $entry, string $level): UniquePriorityQueue
    {
        $queue = new UniquePriorityQueue();
        foreach ($this->handlers as $l => $qs) {
            if (!$this->matchLevel($level, $l)) {
                continue;
            }

            /** @var  UniquePriorityQueue $q */
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
            return TRUE;
        }
        return FALSE;
    }
}