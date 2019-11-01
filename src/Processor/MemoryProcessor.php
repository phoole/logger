<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types = 1);

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
        $context['memory_used'] = number_format(memory_get_usage(TRUE) / 1048575, 2);
        $context['memory_peak'] = number_format(memory_get_peak_usage(TRUE) / 1048575, 2);
        return $context;
    }
}