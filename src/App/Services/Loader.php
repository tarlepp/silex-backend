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
     * Services to expose across the application.
     *
     * @var array
     */
    protected $services = [
        'author.service'    => 'Author',
        'book.service'      => 'Book',
    ];

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
    public function bindServices()
    {
        foreach ($this->services as $service => $class) {
            $share = function() use ($class) {
                $className = '\\App\\Services\\' . $class;

                return new $className($this->app['db'], $this->app['orm.em'], $this->app['validator']);
            };

            $this->app[$service] = $this->app->share($share);
        }
    }
}
