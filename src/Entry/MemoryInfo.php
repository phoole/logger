<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger\Entry;

use Phoole\Logger\Processor\MemoryProcessor;

/**
 * A log entry with predefined message template to log memory usage
 *
 * ```php
 * // initiate log with app id
 * $log = new Logger('MyApp');
 *
 * // add handler to this MemoryInfo
 * $log->addHandler(
 *     LogLevel::INFO,
 *     new LogfileHandler('system.log'),
 *     MemoryInfo::class
 * );
 *
 * // use it
 * $log->info(new MemoryInfo());
 * ```
 *
 * @package Phoole\Logger
 */
class MemoryInfo extends LogEntry
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
    protected static function classProcessors(): array
    {
        return [
            new MemoryProcessor()
        ];
    }
}