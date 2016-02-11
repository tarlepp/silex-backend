<?php
/**
 * /src/App/Components/Swagger/SwaggerController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Components\Swagger;

// Silex components
use Silex\Application;

// Symfony components
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SwaggerController
 *
 * @category    Controller
 * @package     App\Components\Swagger
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class SwaggerController
{
    /**
     * Method to handle API docs generation via Swagger.
     *
     * @todo    Add swagger scan options
     *
     * @param   Application $app
     * @param   Request     $request
     *
     * @return  Response
     */
    public function __invoke(Application $app, Request $request)
    {
        // Get swagger options
        $swaggerOptions = $app['vars']->get('swagger');

        // Scan for Swagger documentation
        $swagger = \Swagger\scan($swaggerOptions['servicePath'], []);

        // Set some env specified parameters
        $swagger->host = $request->getHttpHost();
        $swagger->basePath = $request->getBasePath();

        // Convert to JSON
        $json = (string)$swagger;

        // Create response
        $response = Response::create($json, 200, array('Content-Type' => 'application/json'));
        $response->setEtag(md5($json));
        $response->isNotModified($request);

        return $response;
    }
}
