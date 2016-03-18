<?php
/**
 * /src/App/Security/PreflightRequestMatcher.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Security;

// Silex components
use Silex\Application;

// Symfony components
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PreflightRequestMatcher
 *
 * @category    Provider
 * @package     App\Security
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class PreflightRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * PreflightRequestMatcher constructor.
     *
     * @param   Application $app
     *
     * @return  PreflightRequestMatcher
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Decides whether the rule(s) implemented by the strategy matches the supplied request.
     *
     * @param   Request $request    The request to check for a match
     *
     * @return  boolean             true if the request matches, false otherwise
     */
    public function matches(Request $request)
    {
        return $this->isPreflightRequest($request);
    }

    /**
     * This will match any CORS preflight requests.
     *
     * @param   Request $request
     *
     * @return  boolean
     */
    private function isPreflightRequest(Request $request)
    {
        return $request->getMethod() === 'OPTIONS' && $request->headers->has('Access-Control-Request-Method');
    }
}
