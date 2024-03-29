<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger\Formatter;

use Phoole\Logger\Entry\LogEntryInterface;

/**
 * FormatterInterface
 *
 * @package Phoole\Logger
 */
interface FormatterInterface
{
    /**
     * convert to formatted message
     *
     * @param  LogEntryInterface $entry
     * @return string
     */
    public function format(LogEntryInterface $entry): string;
}