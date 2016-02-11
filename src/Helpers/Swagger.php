<?php
/**
 * /src/Helpers/Swagger.php
 *
 * @category    Helper
 * @package     Helpers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace Helpers;

// 3rd party components
use Swagger\Annotations as SWG;

/**
 * Base definitions for Swagger integration.
 *
 * @SWG\Swagger(
 *      schemes={"http", "https"},
 *      consumes={"application/json"},
 *      produces={"application/json"},
 *      @SWG\Info(
 *           title="silex-backend",
 *           description="Swagger API documentation for application",
 *           version="1.0.0",
 *           @SWG\Contact(
 *               email="tarmo.leppanen@protacon.com",
 *               name="Tarmo Leppänen",
 *               url="https://github.com/tarlepp/silex-backend"
 *           ),
 *           @SWG\License(
 *               name="MIT",
 *               url="https://github.com/tarlepp/silex-backend/blob/master/LICENSE"
 *           )
 *      )
 *  )
 *
 * @SWG\Definition(
 *      definition="Authorization",
 *      type="object",
 *      required={"token"},
 *      @SWG\Property(
 *          property="token",
 *          type="string",
 *      ),
 *      example={
 *          "token": "JWT TOKEN HERE"
 *      },
 *  )
 *
 * @SWG\Definition(
 *      definition="HeaderAuthorization",
 *      type="string",
 *      @SWG\Property(
 *          property="Authorization",
 *          type="string",
 *      ),
 *      example="Authorization: bearer JWT_TOKEN_HERE"
 *  )
 *
 * @SWG\Definition(
 *      definition="ErrorResponse",
 *      type="object",
 *      required={
 *          "message",
 *      },
 *      @SWG\Property(
 *          property="message",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          type="integer",
 *      ),
 *      @SWG\Property(
 *          property="code",
 *          type="integer",
 *      ),
 *      example={
 *          "message": "Error message",
 *          "status": "4xx or 5xx [optional]",
 *          "code": "error coder [optional]"
 *      }
 *  )
 */
