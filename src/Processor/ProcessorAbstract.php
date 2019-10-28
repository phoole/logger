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
 * ProcessorAbstract
 *
 * @package Phoole\Logger
 */
abstract class ProcessorAbstract implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(LogEntryInterface $entry)
    {
        $context = $entry->getContext();
        $entry->setContext($this->updateContext($context));
    }

    /**
     * update info in the $context
     *
     * @param  array $context
     * @return array
     */
    abstract protected function updateContext(array $context): array;
}
