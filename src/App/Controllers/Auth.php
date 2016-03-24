<?php
/**
 * /src/App/Controllers/Auth.php
 *
 * safs
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Controllers;

// Application components
use App\Models\Login;

// Symfony components
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

// 3rd party components
use JsonMapper;
use Swagger\Annotations as SWG;

/**
 * Class Auth
 *
 * This handles following route handling on application:
 *  POST    /auth/login
 *  GET     /auth/profile
 *
 * @mountPoint  /auth
 *
 * @category    Controller
 * @package     App\Controllers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Auth extends Base
{
    /**
     * Method to register all routes for current controller. Note that all routes contains "auth" prefix.
     *
     * @return void
     */
    public function registerRoutes()
    {
        $this->controllers->post('/login', [$this, 'login']);
        $this->controllers->get('/profile', [$this, 'profile']);
    }

    /**
     * User login action which returns JSON Web Token (JWT) on valid request.
     *
     * @SWG\Post(
     *      path="/auth/login",
     *      @SWG\Parameter(
     *          name="credentials",
     *          in="body",
     *          description="User login credentials",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Login"),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Login JWT response",
     *          @SWG\Schema(ref="#/definitions/Authorization"),
     *      ),
     *      @SWG\Response(
     *          response=400,
     *          description="Invalid data",
     *          @SWG\Schema(ref="#/definitions/ErrorResponse"),
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(ref="#/definitions/ErrorResponse"),
     *      ),
     *      @SWG\Response(
     *          response=500,
     *          description="Server error",
     *          @SWG\Schema(ref="#/definitions/ErrorResponse"),
     *      ),
     *  )
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
            $user = $this->app['users']->loadUserByUsername($login->getIdentifier());

            if ($user->verifyPassword($login->getPassword())) {
                // Return token response
                return $this->app->json(['token' => $this->app['security.jwt.encoder']->encode($user->getLoginData())]);
            }
        } catch (\Exception $error) {
            throw new HttpException(401, 'Unauthorized', $error);
        }

        throw new HttpException(401, 'Unauthorized');
    }

    /**
     * Return current user public profile data.
     *
     * @SWG\Get(
     *      path="/auth/profile",
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="JWT authorization header",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/HeaderAuthorization"),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Logged in user profile",
     *          @SWG\Schema(ref="#/definitions/User"),
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(ref="#/definitions/ErrorResponse"),
     *      ),
     *      @SWG\Response(
     *          response=500,
     *          description="Server error",
     *          @SWG\Schema(ref="#/definitions/ErrorResponse"),
     *      ),
     *  )
     *
     * @return  \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function profile()
    {
        return $this->app->json($this->app['user']);
    }
}
