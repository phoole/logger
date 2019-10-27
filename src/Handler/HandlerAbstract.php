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
abstract class HandlerAbstract implements HandlerInterface, FormatterAwareInterface
{
    use FormatterAwareTrait;

    /**
     * @param  FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter = null)
    {
        $this->setFormatter($formatter ?? new DefaultFormatter());
    }

    /**
     * Destructor
     *
     * @access public
     */
    public function __destruct()
    {
        $this->close();
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
        return true;
    }

    /**
     * Get EOL char base on the platform WIN or UNIX
     *
     * @return string
     */
    protected function getEol(): string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return "\r\n";
        } else {
            return "\n";
        }
    }

    /**
     * Close the handler
     */
    protected function close()
    {  
    }

    /**
     * Write to handler's device
     *
     * @param  LogEntryInterface $entry
     */
    abstract protected function write(LogEntryInterface $entry);
}