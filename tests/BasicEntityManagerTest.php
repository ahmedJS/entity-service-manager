<?php

use PHPUnit\Framework\TestCase;
use Vekas\EntityService\BasicEntityManager;
use Vekas\EntityService\Tests\Entities\Message;

class BasicEntityManagerTest extends TestCase {
    static public $entityManager;
    
    static function  setUpBeforeClass(): void
    {
        self::$entityManager = self::getEntityManager();
    }
    
    static function getEntityManager(){ 
        $em = new BasicEntityManager();
        // register default entities
        for($i = 1;$i<5;$i++) {
            $em->addEntity(new Message($i));
        }
        return $em;
    }

    function testRemoveEntity() {
        $messageEntity  = self::$entityManager->find(Message::class,4);
        $this->assertInstanceOf(Message::class,$messageEntity);
        self::$entityManager->remove($messageEntity);
        // flush in the real entity manager
        $messageEntity  = self::$entityManager->find(Message::class,4);
        $this->assertNull($messageEntity);
    }

    function testIfTheOtherEntitiesStillExist() {
        $messageEntity  = self::$entityManager->find(Message::class,3);
        $this->assertNotNull($messageEntity);
    }

}