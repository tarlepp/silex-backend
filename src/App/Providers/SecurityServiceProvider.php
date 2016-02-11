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
use Silex\ServiceProviderInterface;

/**
 * Class SecurityServiceProvider
 *
 * @category    Provider
 * @package     App\Providers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SecurityServiceProvider implements ServiceProviderInterface
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
        $app['cors_preflight_request_matcher'] = new PreflightRequestMatcher($app);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param   Application $app
     *
     * @return  void
     */
    public function boot(Application $app)
    {
    }
}
