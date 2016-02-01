<?php
/**
 * /src/App/Controllers/Interfaces/Rest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers\Interfaces;

/**
 * Interface Rest
 *
 * Interface definition for all application REST controller classes.
 *
 * @category    Interface
 * @package     App\Controllers\Interfaces
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
interface Rest
{
    /**
     * Method to expose necessary services for controller use.
     *
     * @return  void
     */
    public function exposeServices();
}