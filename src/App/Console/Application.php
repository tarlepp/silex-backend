<?php
/**
 * /src/App/Console/Application.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Console;

use App\Application as App;
use Kurl\Silex\Provider\DoctrineMigrationsProvider;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 *
 * Console application class that is used to run all console commands. Note that you need to register all console
 * application providers in this class.
 *
 * @category    Console
 * @package     App\Console
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class Application extends SymfonyApplication
{
    /**
     * @var App
     */
    private $silexApp;

    /**
     * Application constructor.
     *
     * @param   App $silexApp
     *
     * @return  Application
     */
    public function __construct(App $silexApp)
    {
        parent::__construct('Silex - Backend', '0.0.0');

        // Store application
        $this->silexApp = $silexApp;

        // Register console application specified providers
        $this->silexApp->register(new DoctrineMigrationsProvider($this), $this->getDoctrineMigrationsProviderOptions());

        // Boot application
        $this->silexApp->boot();

        $this->getDefinition()->addOption(
            new InputOption(
                '--env',
                '-e',
                InputOption::VALUE_REQUIRED,
                'The Environment name.',
                $this->silexApp->getEnv()
            )
        );
    }

    /**
     * Getter method for DoctrineMigrationsProvider options.
     *
     * @return array
     */
    private function getDoctrineMigrationsProviderOptions()
    {
        return [
            'migrations.directory'  => $this->silexApp->getRootDir() . 'resources/migrations',
            'migrations.name'       => 'App Migrations',
            'migrations.namespace'  => 'App\Migrations',
            'migrations.table_name' => 'db_migrations',
        ];
    }
}
