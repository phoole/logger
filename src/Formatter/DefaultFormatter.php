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

use Phoole\Logger\Entry\LogEntryInterface;

/**
 * DefaultFormatter
 *
 * @package Phoole\Logger
 */
class DefaultFormatter implements FormatterInterface
{
    /**
     * {@inheritDoc}
     */
    public function format(LogEntryInterface $entry): string
    {
        $context = $entry->getContext();

        $mesg = '';
        if (isset($context['__channel'])) {
            $mesg .= '[' . strtoupper($context['__channel']) . '] ';
        }
        $mesg .= $entry . $this->getEol();

        return  $mesg;
    }
   
    /**
     * Get EOL char base on the platform WIN or UNIX
     *
     * @return string
     */
    protected function getEol(): string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return "\r\n";
        } else {
            return "\n";
        }
    }
}
