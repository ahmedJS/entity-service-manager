<?php

use PHPUnit\Framework\TestCase;
use Vekas\EntityService\ArrayEntityServiceRegisterer;
use Vekas\EntityService\BasicEntityManager;
use Vekas\EntityService\PhpFileServiceRegisterer;
use Vekas\EntityService\Tests\Entities\Message;
use Vekas\EntityService\Tests\EntityServices\MessagesService;
use DI\Container;
use Vekas\EntityService\EntityServiceProvider;
use Vekas\EntityService\EntityServiceProviderFactory;

class EntityServiceProviderFactoryTest extends TestCase {

    /**
     * @var EntityServiceProviderFactory
     */
    private static $entityServiceProviderFactory;

    static function  setUpBeforeClass(): void
    {
        $em = self::getEntityManager();
        $di = new Container();
        $registerer = new ArrayEntityServiceRegisterer ($em,$di);

        $entityServiceProviderFactory = new EntityServiceProviderFactory(
            $di,
            $em,
            $registerer
        );
        
        self::$entityServiceProviderFactory = $entityServiceProviderFactory;
    }
    static function getEntityManager(){ 
        $em = new BasicEntityManager();
        // register default entities
        for($i = 1;$i<5;$i++) {
            $em->addEntity(new Message($i));
        }
        return $em;
    }

    function testCreateProviderFromArray() {
        $arr = [
            Message::class => MessagesService::class
        ];
        $providerFactory = self::$entityServiceProviderFactory;
        /** @var  ArrayEntityServiceRegisterer */
        $registerer = $providerFactory->getRegisterer();

        $registerer->setArray($arr);

        $provider = $providerFactory->provide();

        $this->assertInstanceOf(EntityServiceProvider::class,$provider);
        return $provider;
    }

    function testTheProviderContainMessageService() {
        $provider = $this->testCreateProviderFromArray();
        $service = $provider->provide(Message::class,1);
        $this->assertInstanceOf(MessagesService::class,$service);
    }

}