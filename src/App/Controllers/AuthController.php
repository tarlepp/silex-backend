<?php
/**
 * /src/App/Controllers/AuthController.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

// Application components
use App\Models\Login;

// Symfony components
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

// 3rd poarty components
use JsonMapper;

/**
 * Class AuthController
 *
 * This handles following route handling on application:
 *  POST /auth/login
 *
 * @category    Controller
 * @package     App\Controllers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class AuthController extends Base
{
    /**
     * Method to register all routes for current controller. Note that all routes contains 'auth' prefix.
     *
     * @return void
     */
    public function registerRoutes()
    {
        $this->controllers->post('/login', [$this, 'login']);
    }

    /**
     * Implementation for 'POST /auth/login' route.
     *
     * @throws  \JsonMapper_Exception
     * @throws  HttpException
     *
     * @param   Request $request
     *
     * @return  \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login(Request $request)
    {
        /**
         * Map request data to Login model
         *
         * @var Login $login
         */
        $mapper = new JsonMapper();
        $login = $mapper->map(json_decode($request->getContent()), new Login());

        // Validate user input
        $errors = $this->app['validator']->validate($login);

        // Oh noes, we have some errors
        if (count($errors) > 0) {
            throw new HttpException(400, 'Invalid data');
        }

        // Catch all errors on user fetch and send just 401 if something fails
        try {
            $user = $this->app['users']->loadUserByUsername($login->identifier);

            if ($user->verifyPassword($login->password)) {
                $token = $this->app['security.jwt.encoder']->encode($user->getTokenData());

                return $this->app->json(['token' => $token]);
            }
        } catch (\Exception $error) {
            throw new HttpException(401, 'Unauthorized', $error);
        }

        throw new HttpException(401, 'Unauthorized');
    }
}
