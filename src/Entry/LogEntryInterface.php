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
     * @return LogEntryInterface $this
     * @throws InvalidArgumentException
     */
    public function setLevel(string $level): LogEntryInterface;

    /**
     * Set context
     *
     * @param  array $context
     * @return LogEntryInterface $this
     */
    public function setContext(array $context): LogEntryInterface;

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
