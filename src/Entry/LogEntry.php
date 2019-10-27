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

use Phoole\Logger\Processor\ProcessorInterface;

/**
 * Log message
 *  
 * @package Phoole\Logger
 */
class LogEntry implements LogEntryInterface
{
    /**
     * @var string
     */
    protected $message = 'log message';

    /**
     * @var string
     */
    protected $level;

    /**
     * @var array
     */
    protected $context;

    /**
     * processor class name
     * 
     * @var array
     */
    protected $processors = [];

    /**
     * @param  string $level
     * @param  string $message
     * @param  array $context
     */
    public function __construct(string $message = '',  array $context = [])
    {
        if (!empty($message)) {
            $this->message = $message;
        }
        $this->context = $context;

        foreach ($this->processors as $processorClass) {
            (new $processClass)->process($this);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * {@inheritDoc}
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function setLevel(string $level)
    {
        $this->level = $level;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext(array $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->interpolate($this->getMessage(), $this->getContext());
    }

    /**
     * @param  string $message message
     * @param  array  $context
     * @return string result
     */
    protected function interpolate(string $message, array $context): string
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) &&
                (!is_object($val) || method_exists($val, '__toString'))
            ) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}