<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types = 1);

namespace Phoole\Logger\Entry;

use Phoole\Logger\Processor\MemoryProcessor;

/**
 * Log system related message.
 * ```php
 * $log = new Logger('MyApp');
 * $log->addHandler(
 *     new LogfileHandler('system.log'),
 *     LogLevel::INFO,
 *     SystemLog::class
 * );
 * $log->info(new SystemLog());
 * ```
 *
 * @package Phoole\Logger
 */
class SystemLog extends LogEntry
{
    /**
     * default message template
     *
     * @var string
     */
    protected $message = '{memory_used}M memory used, peak usage is {memory_peak}M';

    /**
     * {@inheritDoc}
     */
    public function getProcessors(): array
    {
        return array_merge(
            parent::getProcessors(), [MemoryProcessor::class]
        );
    }
}