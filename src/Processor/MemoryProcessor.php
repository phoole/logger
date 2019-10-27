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

/**
 * MemoryProcessor
 * 
 * @package Phoole\Logger
 */
class MemoryProcessor extends ProcessorAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function updateContext(array $context): array
    {
        $context['memory_used'] = memory_get_usage(true);
        $context['memory_peak'] = memory_get_peak_usage(true);
        return $context;
    }
}