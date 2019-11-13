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
use Phoole\Logger\Formatter\DefaultFormatter;
use Phoole\Logger\Formatter\FormatterInterface;
use Phoole\Logger\Formatter\FormatterAwareTrait;
use Phoole\Logger\Formatter\FormatterAwareInterface;

/**
 * HandlerAbstract
 *
 * @package Phoole\Logger
 */
abstract class HandlerAbstract implements FormatterAwareInterface
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
     * @param  LogEntryInterface $entry
     */
    public function __invoke(LogEntryInterface $entry)
    {
        if ($this->isHandling($entry)) {
            $this->write($entry);
        }
    }

    /**
     * Close the handler if wanted
     */
    protected function close()
    {
    }

    /**
     * Is this handler handling this log ?
     *
     * @param  LogEntryInterface $entry
     * @return bool
     */
    protected function isHandling(LogEntryInterface $entry): bool
    {
        return $entry ? TRUE : TRUE;
    }

    /**
     * Write to handler's device
     *
     * @param  LogEntryInterface $entry
     */
    abstract protected function write(LogEntryInterface $entry);
}