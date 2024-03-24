<?php


namespace Vekas\EntityService;
use Vekas\EntityService\Interfaces\EntityServiceProviderRegistererInterface;
use Vekas\EntityService\EntityServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use InvalidArgumentException;
use Vekas\EntityService\Exceptions\FileDoesNotExistException;

class PhpFileServiceRegisterer implements EntityServiceProviderRegistererInterface {

    private $path;
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

    function setPath($path) {
        $this->path = $path;
    }

    function register(): EntityServiceProvider {
        if ( file_exists($this->path) ) {
            $contents = include($this->path);
            if ( is_array($contents) ) {
                return $this->registerFromArray($contents);
            } else {
                throw new InvalidArgumentException("you must provide php file and with return type array");
            }
        } else {
            throw new FileDoesNotExistException("file path provided : $this->path does not exists");
        }
    }

    function registerFromArray( array $arr) {
        $entityServiceProvider = new EntityServiceProvider($this->container,$this->entityManager);
        foreach($arr as $entityClass => $serviceClass) {
            $entityServiceProvider->registerService($entityClass , $serviceClass);
        }
        return $entityServiceProvider;
    }
}


