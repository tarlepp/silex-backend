<?php
/**
 * /src/App/Controllers/Interfaces/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers\Interfaces;

/**
 * Interface Base
 *
 * Interface definition for all application controller classes.
 *
 * @category    Interface
 * @package     App\Controllers\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Base
{
    /**
     * Method to register all routes for current controller.
     *
     * @return void
     */
    public function registerRoutes();
}