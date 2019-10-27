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
     * @return void
     */
    public function setLevel(string $level);

    /**
     * Set context
     * 
     * @param  array $context
     * @return void
     */
    public function setContext(array $context);

    /**
     * @return string
     */
    public function __toString(): string;
}