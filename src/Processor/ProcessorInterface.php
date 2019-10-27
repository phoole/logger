<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger\Processor;

use Phoole\Logger\Entry\LogEntryInterface;

/**
 * ProcessorInterface
 *
 * @package Phoole\Logger
 */
interface ProcessorInterface
{
    /**
     * Process the log entry
     * 
     * @param  LogEntryInterface
     * @return void
     */
    public function process(LogEntryInterface $entry);
}