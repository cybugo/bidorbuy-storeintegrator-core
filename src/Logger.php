<?php

/**
 * Copyright (c) 2014, 2015, 2016 Bidorbuy http://www.bidorbuy.co.za
 * This software is the proprietary information of Bidorbuy.
 *
 * All Rights Reserved.
 * Modification, redistribution and use in source and binary forms, with or without
 * modification are not permitted without prior written approval by the copyright
 * holder.
 *
 * Vendor: EXTREME IDEA LLC http://www.extreme-idea.com
 */

namespace com\extremeidea\bidorbuy\storeintegrator\core;

use Monolog\Handler\NullHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

/**
 * Class Logger.
 *
 * @package com\extremeidea\bidorbuy\storeintegrator\core
 */
class Logger
{

    const LOGGER_NAME = 'bobsi';

    private $settings;

    protected $logger = null;

    /**
     * Logger constructor.
     *
     * @param Settings $settings settings
     *
     * @return self
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get logger instance.
     *
     * @return MonologLogger
     */
    public function getLogger()
    {

        if (!$this->logger) {
            $logName = Settings::$logsPath . '/bobsi.log';
            $loggingLevel = $this->getLoggingLevel();
            $this->logger = new MonologLogger(self::LOGGER_NAME);
            $handler = new RotatingFileHandler($logName, 30, $loggingLevel);
            $handler->setFilenameFormat('{filename}_{date}', RotatingFileHandler::FILE_PER_DAY);
            if ($loggingLevel == 0) {
                $handler = new NullHandler();
            }
            $this->logger->pushHandler($handler);

            return $this->logger;
        }

        return $this->logger;
    }

    public function getLoggingApp()
    {
        return $this->settings->getLoggingApplication();
    }

    /**
     * Get Logging level
     *
     * @return int
     */
    public function getLoggingLevel()
    {
        $level = $this->settings->getLoggingLevel();

        switch ($level) {
            case 'all':
                $loggerLevel = MonologLogger::INFO;
                break;
            case 'critical':
                $loggerLevel = MonologLogger::CRITICAL;
                break;
            case 'error':
                $loggerLevel = MonologLogger::ERROR;
                break;
            case 'warn':
                $loggerLevel = MonologLogger::WARNING;
                break;
            case 'info':
                $loggerLevel = MonologLogger::INFO;
                break;
            case 'debug':
                $loggerLevel = MonologLogger::DEBUG;
                break;
            case 'disable':
                $loggerLevel = 0;
                break;
            default:
                $loggerLevel = MonologLogger::ERROR;
                break;
        }

        return $loggerLevel;
    }

    /**
     * Proxy function
     *
     * @param string $level   legger function name etc: crit, err, warn for monolog v1.0
     * @param string $message message to log
     *
     * @return void
     */
    protected function log($level, $message)
    {
        $loggingApp = $this->getLoggingApp();
        if ($loggingApp == Settings::NAME_LOGGING_APPLICATION_OPTION_PHP) {
            return;
        }
        $logger = $this->getLogger();
        $logger->$level($message);
    }

    /**
     * Log fatal message
     *
     * @param string $message message to log
     *
     * @return void
     */
    public function critical($message)
    {
        $this->log('critical', $message);
    }

    /**
     * Log error message
     *
     * @param string $message message to log
     *
     * @return void
     */
    public function error($message)
    {
        $this->log('error', $message);
    }

    /**
     * Log warning message
     *
     * @param string $message message to log
     *
     * @return void
     */
    public function warning($message)
    {
        $this->log('warning', $message);
    }

    /**
     * Log info message
     *
     * @param string $message message to log
     *
     * @return void
     */
    public function info($message)
    {
        $this->log('info', $message);
    }

    /**
     * Log debug message
     *
     * @param string $message message to log
     *
     * @return void
     */
    public function debug($message)
    {
        $this->log('debug', $message);
    }

    /**
     * @param $guid
     *
     * @return void
     */
    public function shutdownLog($guid, $lastError)
    {
        if (!$lastError) {
            return;
        }

        $loggingApp = $this->settings->getLoggingApplication();

        $errorType = $lastError['type'];
        $message = $guid . " Type: {$errorType}, message: {$lastError['message']},"
            . " file: {$lastError['file']} , line: {$lastError['line']}";

        preg_match("/bidorbuy/i", $message, $integratorError);

        if ($loggingApp == Settings::NAME_LOGGING_APPLICATION_OPTION_EXTENSION && !$integratorError) {
            // do not log message
            return;
        }

        $funcAssign = [
            E_WARNING => 'warning',
            E_NOTICE => 'warning',
            E_STRICT => 'warning',
            E_DEPRECATED => 'warning'
        ];

        if (isset($funcAssign[$errorType])) {
            $func = $funcAssign[$errorType];
            $this->getLogger()->$func($message);
            return;
        }
        $this->getLogger()->critical($message);
    }
}
