<?php
/**
 * /src/App/Entities/DbMigrations.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * DbMigrations
 *
 * @ORM\Table(name="db_migrations")
 * @ORM\Entity
 */
class DbMigrations extends Base
{
    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $version;

    /**
     * Getter for version data.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Setter for version data.
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
