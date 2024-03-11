<?php
use DI\Container;
use PHPUnit\Framework\TestCase;
use Vekas\EntityService\BasicEntityManager;
use Vekas\EntityService\EntityServiceProvider;
use Vekas\EntityService\Tests\Entities\Message;
use Vekas\EntityService\Tests\EntityServices\MessagesService;

use function PHPUnit\Framework\assertInstanceOf;

class EntityServiceTest extends TestCase {

    static private $entityServiceProvider ;

    static function  setUpBeforeClass(): void
    {
        $em = self::getEntityManager();
        $di = new Container();

        $entityServiceProvider = new EntityServiceProvider($di,$em);

        $entityServiceProvider->registerService(Message::class,MessagesService::class);
        
        self::$entityServiceProvider = $entityServiceProvider;
    }
    
    static function getEntityManager(){ 
        $em = new BasicEntityManager();
        // register default entities
        for($i = 1;$i<5;$i++) {
            $em->addEntity(new Message($i));
        }
        return $em;
    }


    function testGetEntityServiceProvider() {
        $this->assertInstanceOf(EntityServiceProvider::class,self::$entityServiceProvider);
    }
    
    function testGetMessageService() {
        $service = self::$entityServiceProvider->provide(Message::class,1);
        assertInstanceOf(MessagesService::class,$service);
    }

    function testRemoveEntityByItsServiceClass() {
        $service = self::$entityServiceProvider->provide(Message::class,1);
        ob_start();
        $service->remove();
        // flush in the real entity manager
        $message = ob_get_clean();
        $this->assertSame("removing entity : 1",$message);
    }

    function testGetOtherEntityServiceAfterRemoveOne() {
        $service = self::$entityServiceProvider->provide(Message::class,3);
        $this->assertInstanceOf(MessagesService::class,$service);
    }

    function tesdtGetEntityClassFromItsService() {
        $service = self::$entityServiceProvider->provide(Message::class,2);
        $entity = $service->getEntity();
        $this->assertInstanceOf(Message::class,$entity);
    }
}