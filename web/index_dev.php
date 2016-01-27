<?php
/**
 * Main file to handle all requests to Silex backend application in development mode.
 *
 * This file will handle all development environment requests and responses.
 *
 * @todo How to prevent usage of this on production env?
 *
 * @category    Main
 * @package     App
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

// Register autoload for application
require_once __DIR__ . '/../vendor/autoload.php';

// Minimum dependencies at this point
use Symfony\Component\Debug\ErrorHandler;
use App\Core\ExceptionHandler;

// Register error / exception handlers, note that these need to do as soon as possible
ErrorHandler::register();
ExceptionHandler::register();

// Finally create application and run it in 'dev' mode
$app = new App\Application('dev');
$app->run();
