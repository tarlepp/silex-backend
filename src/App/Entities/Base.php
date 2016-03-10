<?php
/**
 * /src/App/Entities/Base.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

// Doctrine components
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Class Base
 *
 * Abstract base class to all application entities.
 *
 * @todo actually implement some common stuff here :D
 *
 * @category    Model
 * @package     App\Entities
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
abstract class Base implements JsonSerializable
{
    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return  array   data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        // TODO add JMS serializer here!

        return [
            'id' => $this->getId(),
        ];
    }
}
