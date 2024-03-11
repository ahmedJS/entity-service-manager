<?php


namespace Vekas\EntityService;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Vekas\EntityService\EntityServiceProvider;

class EntityServiceProviderFactory {
    function __construct(
        private ContainerInterface $container,
        private EntityManagerInterface $entityManager
    ) {}
    
    function registerFromArray($arr) {
        $entityServiceProvider = new EntityServiceProvider($this->container,$this->entityManager);
        foreach($arr as $entityClass => $serviceClass) {
            $entityServiceProvider->registerService($entityClass , $serviceClass);
        }
        return $entityServiceProvider;
    }
}