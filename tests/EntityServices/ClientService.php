<?php


namespace Vekas\EntityService\Tests\EntityServices;
use Vekas\EntityService\EntityService;
use Vekas\EntityService\Interfaces\EntityServiceInterface;
use Vekas\EntityService\Tests\Entities\Message;

class  ClientService  extends EntityService implements EntityServiceInterface{
    function __construct($id, string $entityFqcn,$entityManager, $container)
    {
        parent::__construct($id,$entityFqcn, $entityManager, $container);
    }
}