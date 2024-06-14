<?php

namespace Vekas\EntityService;
use Vekas\EntityService\Exceptions\WrongServiceTargetException;
use Vekas\EntityService\Interfaces\EntityServiceProviderRegistererInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use ReflectionAttribute;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class AttributeServiceRegisterer implements EntityServiceProviderRegistererInterface {

    private EntityServiceProvider $entityServiceProvider;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     */
    function __construct(
        private $entityManager,
        private $container
    ) {
        $this->entityServiceProvider = new EntityServiceProvider($this->container,$this->entityManager);
    }

    function register(): EntityServiceProvider {
        $classesMetadata = $this->getAllMetadata($this->entityManager);
        foreach($classesMetadata as $metadata)  {

        }
        return $this->entityServiceProvider;
    }

    /** @param EntityManager $entityManager */
    function getAllMetadata($entityManager) {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }


    /**
     * @throws WrongServiceTargetException
     * when the service metadata class are applied for method or property 
     */
    function registerByMetadata(ClassMetadata $metadata) {
        $entityName = $metadata->getName();
        $class =  new \ReflectionClass( $entityName );
        $attributes = $class->getAttributes();
        foreach($attributes as $attribute) {
            if($attribute->getName() == Service::class ) {

                // if the target is class
                if ($attribute->getTarget() == 1 ) {

                    /** @var Service */
                    $instance = $attribute->newInstance();
                    
                    $this->entityServiceProvider->registerService(
                        $entityName,
                        $instance->serviceClass,
                    );
                    
                    break;
                } else {
                    throw new WrongServiceTargetException(Service::class . " attribute should target only classes");
                }
            }
        }
    }

    
}