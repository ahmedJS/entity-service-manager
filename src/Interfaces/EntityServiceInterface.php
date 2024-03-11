<?php

namespace Vekas\EntityService\Interfaces;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;

interface EntityServiceInterface {
    function __construct( int $id,string $entityFqcn, EntityManager $entityManager, ContainerInterface $container );
    function remove() : void;
}