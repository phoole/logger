<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Logger
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types = 1);

namespace Phoole\Logger\Handler;

use Throwable;
use Exception;
use LogicException;
use Phoole\Logger\Formatter\FormatterInterface;

/**
 * log to a file
 *
 * @package Phoole\Logger
 */
class LogfileHandler extends StreamHandler
{
    /**
     * file rotation type
     *
     * @const int
     */
    const ROTATE_NONE = 0; // do not rotate
    const ROTATE_DATE = 1; // rotate by date

    /**
     * Constructor
     *
     * @param  string             $path    full path
     * @param  int                $rotate  rotate type
     * @param  FormatterInterface $formatter
     * @throws LogicException if path not writable
     */
    public function __construct(
        string $path,
        int $rotate = self::ROTATE_NONE,
        ?FormatterInterface $formatter = NULL
    ) {
        $this->checkPath($path);
        if (file_exists($path)) {
            $this->doRotation($path, $rotate);
        }
        parent::__construct($path, $formatter);
    }

    /**
     * Check file path
     *
     * @param  string $path
     * @throws LogicException if directory failure etc.
     */
    protected function checkPath(string $path)
    {
        try {
            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, TRUE);
            }
            if (!is_dir($dir) || !is_writable($dir)) {
                throw new Exception("unable to write to $path");
            }
        } catch (Throwable $e) {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * Rotate file on start
     *
     * @param  string $path
     * @param  int    $type
     * @return bool
     */
    protected function doRotation(string $path, int $type): bool
    {
        switch ($type) {
            // by date
            case self::ROTATE_DATE:
                return $this->rotateByDate($path);
            // no rotation
            default:
                return TRUE;
        }
    }

    /**
     * Rotate $path to $path_20160616
     *
     * @param  string $path
     * @param  string $format  date format
     * @return bool
     */
    protected function rotateByDate(string $path, string $format = 'Ymd'): bool
    {
        $time = filemtime($path);
        if ($time < strtotime('today')) {
            return rename($path, $path . '_' . date($format, $time));
        } else {
            return FALSE;
        }
    }
}
