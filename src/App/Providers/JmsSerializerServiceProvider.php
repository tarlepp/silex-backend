<?php
/**
 * /src/App/Providers/JmsSerializerServiceProvider.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Providers;

// Silex components
use Silex\Application;
use Silex\ServiceProviderInterface;

// Symfony components
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

// Doctrine components
use Doctrine\Common\Annotations\AnnotationRegistry;

// JMS components
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;

/**
 * Class JmsSerializerServiceProvider
 *
 * Note that this is just a copy from https://github.com/jdesrosiers/silex-jms-serializer-provider reason for this is
 * composer dependencies that fails :(
 *
 * @category    Provider
 * @package     App\Providers
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class JmsSerializerServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the jms/serializer annotations
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        if ($app->offsetExists('serializer.srcDir')) {
            AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer\Annotation', $app['serializer.srcDir']);
        }
    }

    /**
     * Registet the serializer and serializer.builder services
     *
     * @throws  ServiceUnavailableHttpException
     *
     * @param   Application $app
     */
    public function register(Application $app)
    {
        $app['serializer.namingStrategy.separator'] = null;
        $app['serializer.namingStrategy.lowerCase'] = null;
        $app['serializer.builder'] = $app->share(
            function () use ($app) {
                $serializerBuilder = SerializerBuilder::create()->setDebug($app['debug']);

                if ($app->offsetExists('serializer.annotationReader')) {
                    $serializerBuilder->setAnnotationReader($app['serializer.annotationReader']);
                }

                if ($app->offsetExists('serializer.cacheDir')) {
                    $serializerBuilder->setCacheDir($app['serializer.cacheDir']);
                }

                if ($app->offsetExists('serializer.configureHandlers')) {
                    $serializerBuilder->configureHandlers($app['serializer.configureHandlers']);
                }

                if ($app->offsetExists('serializer.configureListeners')) {
                    $serializerBuilder->configureListeners($app['serializer.configureListeners']);
                }

                if ($app->offsetExists('serializer.objectConstructor')) {
                    $serializerBuilder->setObjectConstructor($app['serializer.objectConstructor']);
                }

                if ($app->offsetExists('serializer.namingStrategy')) {
                    $this->namingStrategy($app, $serializerBuilder);
                }

                if ($app->offsetExists('serializer.serializationVisitors')) {
                    $this->setSerializationVisitors($app, $serializerBuilder);
                }

                if ($app->offsetExists('serializer.deserializationVisitors')) {
                    $this->setDeserializationVisitors($app, $serializerBuilder);
                }

                if ($app->offsetExists('serializer.includeInterfaceMetadata')) {
                    $serializerBuilder->includeInterfaceMetadata($app['serializer.includeInterfaceMetadata']);
                }

                if ($app->offsetExists('serializer.metadataDirs')) {
                    $serializerBuilder->setMetadataDirs($app['serializer.metadataDirs']);
                }

                return $serializerBuilder;
            }
        );

        $app['serializer'] = $app->share(
            function () use ($app) {
                return $app['serializer.builder']->build();
            }
        );
    }

    /**
     * Set the serialization naming strategy
     *
     * @throws  ServiceUnavailableHttpException
     *
     * @param   Application         $app
     * @param   SerializerBuilder   $serializerBuilder
     */
    protected function namingStrategy(Application $app, SerializerBuilder $serializerBuilder)
    {
        if ($app['serializer.namingStrategy'] instanceof PropertyNamingStrategyInterface) {
            $namingStrategy = $app['serializer.namingStrategy'];
        } else {
            switch ($app['serializer.namingStrategy']) {
                case 'IdenticalProperty':
                    $namingStrategy = new IdenticalPropertyNamingStrategy();
                    break;
                case 'CamelCase':
                    $namingStrategy = new CamelCaseNamingStrategy(
                        $app['serializer.namingStrategy.separator'],
                        $app['serializer.namingStrategy.lowerCase']
                    );
                    break;
                default:
                    throw new ServiceUnavailableHttpException(
                        null,
                        'Unknown property naming strategy \'' . $app['serializer.namingStrategy'] . '\'.  ' .
                        'Allowed values are \'IdenticalProperty\' or \'CamelCase\''
                    );
            }

            $namingStrategy = new SerializedNameAnnotationStrategy($namingStrategy);
        }

        $serializerBuilder->setPropertyNamingStrategy($namingStrategy);
    }

    /**
     * Override default serialization visitors
     *
     * @param   Application         $app
     * @param   SerializerBuilder   $serializerBuilder
     */
    protected function setSerializationVisitors(Application $app, SerializerBuilder $serializerBuilder)
    {
        $serializerBuilder->addDefaultSerializationVisitors();

        foreach ($app['serializer.serializationVisitors'] as $format => $visitor) {
            $serializerBuilder->setSerializationVisitor($format, $visitor);
        }
    }

    /**
     * Override default deserialization visitors
     *
     * @param   Application         $app
     * @param   SerializerBuilder   $serializerBuilder
     */
    protected function setDeserializationVisitors(Application $app, SerializerBuilder $serializerBuilder)
    {
        $serializerBuilder->addDefaultDeserializationVisitors();

        foreach ($app['serializer.deserializationVisitors'] as $format => $visitor) {
            $serializerBuilder->setDeserializationVisitor($format, $visitor);
        }
    }
}
