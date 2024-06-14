<?php

use PHPUnit\Framework\TestCase;
use Vekas\EntityService\ArrayEntityServiceRegisterer;
use Vekas\EntityService\BasicEntityManager;
use Vekas\EntityService\PhpFileServiceRegisterer;
use Vekas\EntityService\Tests\Entities\Message;
use Vekas\EntityService\Tests\EntityServices\MessagesService;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Vekas\EntityService\AttributeServiceRegisterer;
use Vekas\EntityService\EntityServiceProvider;
use Vekas\EntityService\EntityServiceProviderFactory;
use Vekas\EntityService\Tests\Entities\Client;

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

    function testCreateProviderByEntityManager() {
        $emp = $this->getEntityManagerProviderByAttributes();
        $this->assertInstanceOf(EntityServiceProvider::class,$emp);
        $this->assertGreaterThan(0,count($emp->getServices()));
    }

    function getEntityManagerProviderByAttributes() {
        $em = $this->createMock(EntityManager::class);

        // ClassMetadata object used in the AttributeServiceRegisterer
        $metadataFactory = $this->createMock(ClassMetadataFactory::class);

        // ClassMetadata object used in the AttributeServiceRegisterer
        $classMetadata = $this->createMock(ClassMetadata::class);

        // this method is used in the AttributeServiceRegisterer
        $classMetadata->method("getName")
            ->willReturn(Client::class);

        $metadataFactory->method("getAllMetadata")
            ->willReturn([$classMetadata]);

        $em->method("getMetadataFactory")
            ->willReturn($metadataFactory);

            
        $di = new Container();
        
        // instantiate registerer
        $registerer = new AttributeServiceRegisterer ($em,$di);

        $entityServiceProviderFactory = new EntityServiceProviderFactory(
            $registerer
        );


        $entityServiceProvider = $entityServiceProviderFactory->provide();
        
        return $entityServiceProvider;
    }


}