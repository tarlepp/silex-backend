<?php
/**
 * Main file to handle all requests to Silex backend application in production mode.
 *
 * This file will handle all production environment requests and responses.
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
ExceptionHandler::register(false);

// Finally create application and run it in 'prod' mode
$app = new App\Application('prod');
$app->run();
