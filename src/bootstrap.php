<?php
require __DIR__ . '/../vendor/autoload.php';

// configure the Simple Logging Facade for PSR-3 loggers with a Monolog backend
\bitExpert\Slf4PsrLog\LoggerFactory::registerFactoryCallback(function ($channel) {
    if (!\Monolog\Registry::hasLogger($channel)) {
        \Monolog\Registry::addLogger(new \Monolog\Logger($channel));
    }
    return \Monolog\Registry::getInstance($channel);
});
