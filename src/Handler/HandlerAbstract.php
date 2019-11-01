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

use Phoole\Logger\{
    Entry\LogEntryInterface,
    Formatter\DefaultFormatter,
    Formatter\FormatterInterface,
    Formatter\FormatterAwareTrait,
    Formatter\FormatterAwareInterface};

/**
 * HandlerAbstract
 *
 * @package Phoole\Logger
 */
abstract class HandlerAbstract implements HandlerInterface, FormatterAwareInterface
{
    use FormatterAwareTrait;

    /**
     * @param  FormatterInterface $formatter
     */
    public function __construct(?FormatterInterface $formatter = NULL)
    {
        $this->setFormatter($formatter ?? new DefaultFormatter());
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Close the handler
     */
    protected function close()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function handle(LogEntryInterface $entry): LogEntryInterface
    {
        if ($this->isHandling()) {
            $this->write($entry);
        }
        return $entry;
    }

    /**
     * Is this handler handling this log ?
     *
     * @return bool
     */
    protected function isHandling(): bool
    {
        return TRUE;
    }

    /**
     * Write to handler's device
     *
     * @param  LogEntryInterface $entry
     */
    abstract protected function write(LogEntryInterface $entry);
}
