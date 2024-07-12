<?php

declare(strict_types=1);

namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CustomRotatingFileHandler extends RotatingFileHandler
{
    public function __construct(
        string $filename,
        int $maxFiles = 0,
        $level = Logger::DEBUG,
        bool $bubble = true,
        ?int $filePermission = null,
        bool $useLocking = false
    ) {
        parent::__construct($filename, $maxFiles, $level, $bubble, $filePermission, $useLocking);
        $this->setFilenameFormat('{filename}-{date}', self::FILE_PER_MONTH);
    }

    protected function write(array $record): void
    {
        // Ensure the directory exists
        $dir = dirname($this->url);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Ensure the file exists
        if (!file_exists($this->url)) {
            touch($this->url);
            if (isset($this->filePermission)) {
                chmod($this->url, $this->filePermission);
            }
        }

        parent::write($record);
    }
}
