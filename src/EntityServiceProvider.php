<?php

namespace Vekas\EntityService;

use Doctrine\ORM\EntityManagerInterface;
use Vekas\EntityService\Exceptions\ServiceNotExistException;
use Vekas\EntityService\Interfaces\EntityServiceProviderInterface;
use Vekas\EntityService\Interfaces\EntityServiceInterface;
use Psr\Container\ContainerInterface;


/**
 * class that is responsible for provide a business logic layer for every entity registered
 */
class EntityServiceProvider implements EntityServiceProviderInterface{

    private $services = [];


    /**
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    function __construct(
        private  $container,
        private  $entityManager
    ){}


    /**
     * provide a service class for given entity class
     * @throws ServiceNotExistException if provider not registered
     * @return EntityService
     */
    function provide($class,$id) {
        $entityClasses = array_keys($this->services);
        if(($i = array_search($class,$entityClasses)) !== false) {
            // construct new service of the entity class required
            return new ($this->services[$entityClasses[$i]])($id,$class,$this->entityManager, $this->container);
        } 
        throw new ServiceNotExistException("entity provider not registered");
    }

    function registerService(string $entityClass,string $serviceClass ) {
        $this->services[$entityClass] = $serviceClass;
    }

    function getServices() {
        return $this->services;
    }

}