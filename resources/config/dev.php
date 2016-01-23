<?php
/**
 * /resources/config/dev.php
 *
 * Development environment (dev) basic 'static' configuration. Purpose of this file is basically just enable 'debug'
 * mode on Silex application.
 *
 * Note that this configuration is only used if you're using following url to this Silex application:
 *
 *  http(s)://your_url/index_dev.php/
 *
 * @category    Config
 * @package     App
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;
