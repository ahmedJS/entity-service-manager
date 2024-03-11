<?php

namespace Vekas\EntityService\Interfaces;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

interface EntityServiceInterface {
    function __construct( int $id,string $entityFqcn, EntityManagerInterface $entityManager, ContainerInterface $container );
    function remove() : void;
}