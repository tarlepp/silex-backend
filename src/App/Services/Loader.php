<?php
/**
 * /src/App/Services/Loader.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Services;

// Silex components
use Silex\Application;

/**
 * Class Loader
 *
 * @category    Services
 * @package     App\Services
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Loader
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Loader constructor.
     *
     * @param   Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Method to share specified services across application.
     *
     * @return  void
     */
    public function bindServicesIntoContainer()
    {
        $this->app['author.service'] = $this->app->share(function() {
            return new AuthorService($this->app['db'], $this->app['orm.em'], $this->app['validator']);
        });
    }
}
