<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types = 1);

namespace Phoole\Logger\Formatter;

/**
 * FormatterAwareInterface
 *
 * @package Phoole\Logger
 */
interface FormatterAwareInterface
{
    /**
     * @param  FormatterInterface
     * @return void
     */
    public function setFormatter(FormatterInterface $formatter);

    /**
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface;
}