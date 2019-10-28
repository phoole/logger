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

use Psr\Log\LogLevel;
use Phoole\Logger\Entry\LogEntryInterface;

/**
 * ANSI Color formatter
 *
 * @package Phoole\Logger
 */
class AnsiFormatter extends DefaultFormatter
{
    /**
     * foreground color
     *
     * @const
     */
    const FGCOLOR_BLACK          = "\033[0;30m";
    const FGCOLOR_RED            = "\033[0;31m";
    const FGCOLOR_GREEN          = "\033[0;32m";
    const FGCOLOR_YELLOW         = "\033[0;33m";
    const FGCOLOR_BLUE           = "\033[0;34m";
    const FGCOLOR_MAGENTA        = "\033[0;35m";
    const FGCOLOR_CYAN           = "\033[0;36m";
    const FGCOLOR_GRAY           = "\033[0;37m";
    const FGCOLOR_DARK_GRAY      = "\033[1;30m";
    const FGCOLOR_BRIGHT_RED     = "\033[1;31m";
    const FGCOLOR_BRIGHT_GREEN   = "\033[1;32m";
    const FGCOLOR_BRIGHT_YELLOW  = "\033[1;33m";
    const FGCOLOR_BRIGHT_BLUE    = "\033[1;34m";
    const FGCOLOR_BRIGHT_MAGENTA = "\033[1;35m";
    const FGCOLOR_BRIGHT_CYAN    = "\033[1;36m";
    const FGCOLOR_WHITE          = "\033[1;37m";

    /**
     * background color
     *
     * @const
     */
    const BGCOLOR_BLACK          = "\033[40m";
    const BGCOLOR_RED            = "\033[41m";
    const BGCOLOR_GREEN          = "\033[42m";
    const BGCOLOR_YELLOW         = "\033[43m";
    const BGCOLOR_BLUE           = "\033[44m";
    const BGCOLOR_MAGENTA        = "\033[45m";
    const BGCOLOR_CYAN           = "\033[46m";
    const BGCOLOR_WHITE          = "\033[47m";
    const DECO_BOLD              = "\033[1m";
    const DECO_UNDERLINE         = "\033[4m";
    const DECO_BLINK             = "\033[5m";
    const DECO_REVERSE           = "\033[7m";
    const DECO_CROSS             = "\033[9m";
    const DECO_END               = "\033[0m";

    /**
     * Color definitions for different log levels
     *
     * format  [ fgColor, bgColor, textDeco ]
     *
     * @var     array
     * @access  protected
     */
    protected $colors = array(
        LogLevel::DEBUG     => [self::FGCOLOR_GRAY, '', ''],
        LogLevel::INFO      => ['', '', ''],
        LogLevel::NOTICE    => [self::FGCOLOR_BRIGHT_GREEN, '', ''],
        LogLevel::WARNING   => [self::FGCOLOR_BRIGHT_YELLOW, '', ''],
        LogLevel::ERROR     => [self::FGCOLOR_BRIGHT_RED, '', ''],
        LogLevel::CRITICAL  => [self::FGCOLOR_BRIGHT_RED, '', self::DECO_UNDERLINE],
        LogLevel::ALERT     => [self::FGCOLOR_BRIGHT_RED, self::BGCOLOR_WHITE, ''],
        LogLevel::EMERGENCY => [self::FGCOLOR_BRIGHT_RED, self::BGCOLOR_WHITE, self::DECO_BLINK],
    );

    /**
     * {@inheritDoc}
     */
    public function format(LogEntryInterface $entry): string
    {
        $text = parent::format($entry);
        return $this->addColor($entry->getLevel(), $text);
    }

    /**
     * add ansi color to text
     *
     * @param  string $level
     * @param  string $text
     * @return string
     */
    protected function addColor(string $level, string $text): string
    {
        list($fgColor, $bgColor, $deColor) = $this->colors[$level];
        $prefix  = $fgColor . $bgColor . $deColor;
        $suffix  = $prefix ? self::DECO_END : '';
        return $prefix . $text . $suffix;
    }
}
