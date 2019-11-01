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

use Psr\Log\LogLevel;

/**
 * @package Phoole\Logger
 */
trait LogLevelTrait
{
    /**
     * convert to numeric values
     *
     * @var array
     */
    protected $convert = [
        LogLevel::DEBUG => 100,
        LogLevel::INFO => 200,
        LogLevel::NOTICE => 300,
        LogLevel::WARNING => 400,
        LogLevel::ERROR => 500,
        LogLevel::CRITICAL => 600,
        LogLevel::ALERT => 700,
        LogLevel::EMERGENCY => 800
    ];
}