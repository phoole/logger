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
use Phoole\Logger\Entry\LogEntry;
use Phoole\Logger\Entry\LogEntryInterface;
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
     * @var string
     */
    protected $channel;

    /**
     * Channel usually is APP ID
     *
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
        $entry = ($this->initEntry($message, $level, $context))->process();

        foreach ($this->getHandlers($entry) as $handler) {
            $handler($entry);
        }
    }

    /**
     * @param  LogEntryInterface|string $message
     * @param  string                   $level
     * @param  array                    $context
     * @return LogEntryInterface
     * @throws \InvalidArgumentException if message not right
     */
    protected function initEntry($message, string $level, array $context): LogEntryInterface
    {
        $entry = $this->validate($message);

        // update channel name in context
        $this->setChannel($context);

        return $entry
            ->setLevel($level)
            ->setContext(array_merge($entry->getContext(), $context));
    }

    /**
     * @param $message
     * @return LogEntryInterface
     * @throws \InvalidArgumentException if message not right
     */
    protected function validate($message): LogEntryInterface
    {
        if (is_string($message)) {
            $entry = new LogEntry($message);
        } elseif (is_object($message) && $message instanceof LogEntryInterface) {
            $entry = $message;
        } else {
            throw new \InvalidArgumentException("not valid message " . (string) $message);
        }
        return $entry;
    }

    /**
     * Set channel name in context
     *
     * @param  array &$context
     */
    protected function setChannel(array &$context)
    {
        if (!isset($context['__channel'])) {
            $context['__channel'] = $this->channel;
        }
    }
}