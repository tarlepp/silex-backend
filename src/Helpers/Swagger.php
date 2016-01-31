<?php
/**
 * /src/Helpers/Swagger.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace Helpers;

// 3rd party components
use Swagger\Annotations as SWG;

/**
 * Class Swagger
 *
 * This isn't a real class, basically this "class" only contains some generic Swagger
 *
 * @category    Helper
 * @package     Helpers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Swagger
{
    /**
     * Responses
     *
     * @SWG\ResponseMessage(
     *      partial="Error400",
     *      code=400,
     *      message={
     *          "message": "Invalid data",
     *          "status": 400,
     *          "code": 0,
     *      }
     *  )
     *
     * @SWG\ResponseMessage(
     *      partial="Error401",
     *      code=401,
     *      message={
     *          "message": "Unauthorized",
     *          "status": 401,
     *          "code": 0,
     *      }
     *  )
     *
     * @SWG\ResponseMessage(
     *      partial="Error404",
     *      code=401,
     *      message={
     *          "message": "Not found",
     *          "status": 404,
     *          "code": 0,
     *      }
     *  )
     *
     * @SWG\ResponseMessage(
     *      partial="ErrorJWT",
     *      code=401,
     *      message={
     *          "message": "A Token was not found in the TokenStorage.",
     *      },
     *  )
     */

    /**
     * Parameters
     *
     * @SWG\Parameter(
     *      partial="Authorization",
     *      name="Authorization",
     *      description="JWT header that is needed for protected API endpoints. Example value: 'Bearer put_jwt_token_here'",
     *      paramType="header",
     *      required=true,
     *      allowMultiple=false,
     *      type="string",
     *  )
     *
     * @SWG\Parameter(
     *      partial="Credentials",
     *      name="credentials",
     *      description="JSON object that contains necessary credential data.",
     *      paramType="body",
     *      required=true,
     *      allowMultiple=false,
     *      type="Login",
     *  )
     *
     * @SWG\Parameter(
     *      partial="QueryIdentifier",
     *      name="id",
     *      description="Object ID",
     *      paramType="path",
     *      required=true,
     *      allowMultiple=false,
     *      type="integer",
     *  )
     */

    /**
     * Models
     *
     * @SWG\Model(
     *      id="Authorization",
     *      description="JSON object whi",
     *  )
     *
     * @SWG\Property(
     *      name="token",
     *      type="string",
     *  )
     */
}