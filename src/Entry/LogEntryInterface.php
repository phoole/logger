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

use Psr\Log\InvalidArgumentException;

/**
 * Log message
 *
 * @package Phoole\Logger
 */
interface LogEntryInterface
{
    /**
     * Get the text message
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Get the level
     *
     * @return string
     */
    public function getLevel(): string;

    /**
     * Get the context
     *
     * @return array
     */
    public function getContext(): array;

    /**
     * @param  string $level
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setLevel(string $level);

    /**
     * Set context
     *
     * @param  array $context
     * @return $this
     */
    public function setContext(array $context);

    /**
     * array of processor classname
     *
     * @return string[]
     */
    public function getProcessors(): array;

    /**
     * @return string
     */
    public function __toString(): string;
}