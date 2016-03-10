<?php
/**
 * /src/App/Providers/SecurityServiceProvider.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Providers;

// Application components
use App\Security\PreflightRequestMatcher;

// Silex components
use Silex\Application;
use Silex\Provider\SecurityServiceProvider as Base;

/**
 * Class SecurityServiceProvider
 *
 * @category    Provider
 * @package     App\Providers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SecurityServiceProvider extends Base
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param   Application $app
     *
     * @return  void
     */
    public function register(Application $app)
    {
        parent::register($app);

        $app['cors_preflight_request_matcher'] = new PreflightRequestMatcher($app);
    }
}
