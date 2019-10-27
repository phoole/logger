<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Logger\Handler;

use Psr\Log\LogLevel;
use Phoole\Logger\Entry\LogEntryInterface;
use Phoole\Logger\Formatter\FormatterInterface;

/**
 * log to syslog on UNIX type system
 * 
 * @package Phoole\Logger
 */
class SyslogHandler extends HandlerAbstract
{
    /**
     * syslog facility
     *
     * @var    int
     */
    protected $facility;
    
    /**
     * syslog options
     *
     * @var    int
     */
    protected $logopts;

    /**
     * syslog levels
     *
     * @var    array
     * @access protected
     */
    protected $priorities = [
        LogLevel::DEBUG     => \LOG_DEBUG,
        LogLevel::INFO      => \LOG_INFO,
        LogLevel::NOTICE    => \LOG_NOTICE,
        LogLevel::WARNING   => \LOG_WARNING,
        LogLevel::ERROR     => \LOG_ERR,
        LogLevel::CRITICAL  => \LOG_CRIT,
        LogLevel::ALERT     => \LOG_ALERT,
        LogLevel::EMERGENCY => \LOG_EMERG,
    ];

    /**
     * @param  int $facility
     * @param  int $logOpts
     * @param  FormatterInterface $formatter
     */
    public function __construct(
        int $facility = \LOG_USER,
        int $logOpts = \LOG_PID,
        FormatterInterface $formatter = null
    ) {
        $this->facility = $facility;
        $this->logopts = $logOpts;
        parent::__construct($formatter);
    }
    
    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $entry)
    {
        $context = $entry->getContext();
        $ident = $context['channel'] ?? 'LOG';

        if (!openlog($ident, $this->logopts, $this->facility)) {
            throw new \LogicException("openlog() failed");
        }

        syslog(
            $this->priorities[$entry->getLevel()],
            $this->getFormatter()->format($entry)
        );

        closelog();
    }
}