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
 * ProcessorAwareInterface
 *
 * @package Phoole\Logger
 */
interface ProcessorAwareInterface
{
    /**
     * Execute all the processors
     *
     * @return $this
     */
    public function process();

    /**
     * Set processors for THIS class
     *
     * @param  callable ...$callables
     * @return string called class name
     * @throws \LogicException if not valid processor found
     */
    public static function addProcessor(callable ...$callables): string;
}