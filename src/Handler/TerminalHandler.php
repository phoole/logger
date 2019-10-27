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
use Phoole\Logger\Formatter\AnsiFormatter;
use Phoole\Logger\Formatter\FormatterInterface;

/**
 * log to a terminal
 * 
 * @package Phoole\Logger
 */
class TerminalHandler extends StreamHandler
{
    /**
     * @param  string|resource $stream
     * @param  FormatterInterface $formatter
     */
    public function __construct(
        $stream = 'php://stderr',
        FormatterInterface $formatter = null
    ) {
        if (!in_array($stream, ['php://stderr', 'php://stdout'])) {
            throw new \LogicException("unknown stream");
        }
        parent::__construct($stream, $formatter ?? new AnsiFormatter());
    }

    /**
     * {@inheritDoc}
     */
    protected function isHandling(): bool
    {
        return 'cli' === php_sapi_name();
    }
}