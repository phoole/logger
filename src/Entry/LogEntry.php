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

use Psr\Log\LogLevel;
use Psr\Log\InvalidArgumentException;
use Phoole\Logger\Processor\ProcessorAwareTrait;

/**
 * Log message prototype
 *
 * @package Phoole\Logger
 */
class LogEntry implements LogEntryInterface
{
    use LogLevelTrait;
    use ProcessorAwareTrait;

    /**
     * message template
     *
     * @var string
     */
    protected $message = 'log message';

    /**
     * @var string
     */
    protected $level = LogLevel::INFO;

    /**
     * @var array
     */
    protected $context;

    /**
     * @param  string $message
     * @param  array  $context
     */
    public function __construct(string $message = '', array $context = [])
    {
        if (!empty($message)) {
            $this->message = $message;
        }
        $this->context = $context;
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
    public function setLevel(string $level)
    {
        if (!isset($this->convert[$level])) {
            throw new InvalidArgumentException("unknown log level");
        }
        $this->level = $level;
        return $this;
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
    public function setContext(array $context)
    {
        $this->context = $context;
        return $this;
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
    public function __toString(): string
    {
        $this->process();
        return $this->interpolate($this->getMessage(), $this->getContext());
    }

    /**
     * @param  string $message  message
     * @param  array  $context
     * @return string result
     */
    protected function interpolate(string $message, array $context): string
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (
                !is_array($val) &&
                (!is_object($val) ||
                    method_exists($val, '__toString'))
            ) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}