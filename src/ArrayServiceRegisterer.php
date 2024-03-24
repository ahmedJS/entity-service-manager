<?php 


namespace Vekas\EntityService;
use Vekas\EntityService\Interfaces\EntityServiceProviderRegistererInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class ArrayEntityServiceRegisterer implements EntityServiceProviderRegistererInterface {

    private array $array;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     * 
     */
    function __construct(
        private $entityManager,
        private $container
    ) {

    }

    function setPath(array $array) {
        $this->array = $array;
    }

    function register(): EntityServiceProvider {
        $entityServiceProvider = new EntityServiceProvider($this->container,$this->entityManager);
        foreach($this->array as $entityClass => $serviceClass) {
            $entityServiceProvider->registerService($entityClass , $serviceClass);
        }
        return $entityServiceProvider;
    }
}