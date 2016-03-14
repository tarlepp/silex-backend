<?php
/**
 * /src/App/Console/Application.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Console;

// Application components
use App\Application as App;

// Symfony components
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

// Doctrine components
use Doctrine\DBAL\Tools\Console\Command as DBALCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Command as ORMCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

// 3rd party components
use Kurl\Silex\Provider\DoctrineMigrationsProvider;

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
     * @todo Read version from somewhere?
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

        // Register pure Doctrine commands
        $this->registerDoctrineCommands();

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
     * @see https://github.com/kurlltd/silex-doctrine-migrations-provider#usage
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

    /**
     * Helper method to register some Doctrine core console commands.
     *
     * @return  void
     */
    private function registerDoctrineCommands()
    {
        // Get EntityManager
        $em = $this->silexApp['orm.em'];

        // Create helper set...
        $helperSet = new HelperSet([
            'db' => new ConnectionHelper($em->getConnection()),
            'em' => new EntityManagerHelper($em),
        ]);

        // ... and attach it to console application
        $this->setHelperSet($helperSet);

        // Finally add those DBAL and ORM commands
        $this->addCommands([
            // DBAL Commands
            new DBALCommand\RunSqlCommand(),
            new DBALCommand\ImportCommand(),

            // ORM Commands
            new ORMCommand\ClearCache\MetadataCommand(),
            new ORMCommand\ClearCache\ResultCommand(),
            new ORMCommand\ClearCache\QueryCommand(),
            new ORMCommand\SchemaTool\CreateCommand(),
            new ORMCommand\SchemaTool\UpdateCommand(),
            new ORMCommand\SchemaTool\DropCommand(),
            new ORMCommand\EnsureProductionSettingsCommand(),
            new ORMCommand\ConvertDoctrine1SchemaCommand(),
            new ORMCommand\GenerateRepositoriesCommand(),
            new ORMCommand\GenerateEntitiesCommand(),
            new ORMCommand\GenerateProxiesCommand(),
            new ORMCommand\ConvertMappingCommand(),
            new ORMCommand\RunDqlCommand(),
            new ORMCommand\ValidateSchemaCommand(),
        ]);
    }
}
