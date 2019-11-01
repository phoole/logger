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

use LogicException;
use Phoole\Logger\Entry\LogEntryInterface;
use Phoole\Logger\Formatter\FormatterInterface;

/**
 * @package Phoole\Logger
 */
class StreamHandler extends HandlerAbstract
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * @param  string|resource    $stream
     * @param  FormatterInterface $formatter
     */
    public function __construct($stream, ?FormatterInterface $formatter = NULL)
    {
        $this->stream = $this->openStream($stream);
        parent::__construct($formatter);
    }

    /**
     * Open stream for writing
     *
     * @param  string|resource $path
     * @return resource
     * @throws LogicException if open failure
     */
    protected function openStream($path)
    {
        if (is_string($path)) {
            if (FALSE === strpos($path, '://')) {
                $path = 'file://' . $path;
            }
            return fopen($path, 'a');
        }
        if (is_resource($path)) {
            return $path;
        }
        throw new LogicException("failed to open stream");
    }

    /**
     * close the stream
     */
    protected function close()
    {
        if ($this->stream) {
            fclose($this->stream);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $entry)
    {
        if ($this->stream) {
            $msg = $this->getFormatter()->format($entry);
            flock($this->stream, LOCK_EX);
            fwrite($this->stream, $msg);
            flock($this->stream, LOCK_UN);
        }
    }
}
