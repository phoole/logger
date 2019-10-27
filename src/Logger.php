<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;
use Phoole\Logger\Handler\HandlerAwareTrait;
use Phoole\Logger\Handler\HandlerAwareInterface;

/**
 * Logger
 *
 * @package Phoole\Logger
 */
class Logger implements LoggerInterface, HandlerAwareInterface
{
    use LoggerTrait;
    use HandlerAwareTrait;

    /**
     * @param  string $channel
     */
    public function __construct(string $channel)
    {
        $this->channel = $channel;
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = array())
    {
        // init the log entry
        $entry = $this->initEntry($message, $level, $context);
        
        // handle the entry
        foreach($this->getHandlers($entry) as $handler) {
            $entry = $handler->handle($entry);
        }
    }

    /**
     * @param  LogEntryInterface|string $message
     * @param  string $level
     * @param  array $context
     * @return LogEntryInterface
     */
    protected function initEntry($message, string $level, array $context): LogEntryInterface
    {
        if (is_object($message) && $message instanceof LogEntryInterface) {
            $entry = $message;
        } else {
            $entry = new LogEntry($message);
        }

        $this->setChannel($context);

        $entry->setLevel($level);
        $entry->setContext(array_merge($entry->getContext(), $context));
        return $entry;
    }

    /**
     * @param  array &$context
     */
    protected function setChannel(array &$context)
    {
        if (!isset($context['channel'])) {
            $context['__channel'] = $this->channel;
        }
    }
}