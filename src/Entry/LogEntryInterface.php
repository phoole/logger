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

use Psr\Log\InvalidArgumentException;
use Phoole\Logger\Processor\ProcessorAwareInterface;

/**
 * Log message
 *
 * @package Phoole\Logger
 */
interface LogEntryInterface extends ProcessorAwareInterface
{
    /**
     * Get the text message template (raw message)
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param  string $level
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setLevel(string $level);

    /**
     * Get the level
     *
     * @return string
     */
    public function getLevel(): string;

    /**
     * Set context
     *
     * @param  array $context
     * @return $this
     */
    public function setContext(array $context);

    /**
     * Get the context
     *
     * @return array
     */
    public function getContext(): array;

    /**
     * @return string
     */
    public function __toString(): string;
}