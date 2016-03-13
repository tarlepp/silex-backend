<?php
/**
 * /src/App/Repositories/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repositories;

// Application entities
use App\Entities;

// Doctrine components
use Doctrine\ORM\EntityRepository;

/**
 * Base doctrine repository class for entities.
 *
 * @category    Doctrine
 * @package     App\Repositories
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base extends EntityRepository
{
    // Implement custom entity query methods here
}
