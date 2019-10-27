<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger\Formatter;

/**
 * FormatterAwareTrait
 *
 * @package Phoole\Logger
 */
trait FormatterAwareTrait
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * {@inheritDoc}
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }
}