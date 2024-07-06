<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class CreateMonthlyLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        // Dapatkan path dari konfigurasi
        $logPath      = $config['path'];
        $logDirectory = dirname($logPath);
        $logFileName  = basename($logPath, '.log');

        // Buat logger instance
        $logger       = new Logger($logFileName);

        // Tambah handler yang berotasi setiap bulan
        $handler      = new RotatingFileHandler($logDirectory . '/' . $logFileName . '.log', 0, $config['level'], true, 0664, true);
        $handler->setFilenameFormat($logFileName . '-{date}', 'Y-m'); // Format YYYYMM

        $logger->pushHandler($handler);

        return $logger;
    }
}
