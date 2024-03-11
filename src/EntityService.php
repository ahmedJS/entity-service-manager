<?php


namespace Vekas\EntityService;

use App\interfaces\EntityServiceInterface;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Vekas\EntityService\Exceptions\EntityNotExistException;
use Vekas\EntityService\Exceptions\InvalidConfigurationException;
use Psr\Container\NotFoundExceptionInterface;
use Doctrine\ORM\ORMInvalidArgumentException;
use Vekas\EntityService\Exceptions\EntityNotRegisteredException;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @template T
 */
abstract class EntityService  {
    /**
     * this is must be overrided by the extended class
     */
    protected $entityClassName = "";
    
    /**
     * @var T
     */
    protected $entity;
    
    protected  $entityManager;

    protected ContainerInterface $container;

    /**
     * @param  EntityManagerInterface $entityManager 
     * @param  ContainerInterface $container 
     */
    function __construct(
        int $id,
        string $entityFqcn,
        $entityManager,
        $container )
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->entityClassName = $entityFqcn;
        $this->registerEntity($id);
    }

    /**
     * @throws EntityNotExistException when enity not exist
     * @throws InvalidConfigurationException when the entity class name not overrided in the extended clas
     * @throws InvalidConfigurationException when the entity class name is not registered with entities
     */
    protected function registerEntity($id) {

        try {
            
            // if the entity class name not set by the extended class this will throw an exception
            if ($this->entityClassName == '' ) {
                throw new InvalidConfigurationException(
                    "it is necessary to override the protected \$entityClassName in the extended class");
            }

            $entity = $this->entityManager->find($this->entityClassName,$id);

            if(!$entity) {throw new EntityNotExistException(); };
            
            $this->entity = $entity;


        } catch (ORMInvalidArgumentException $e) {
            throw $e;
        } catch (ORMException $e) {
            throw new InvalidConfigurationException(
                "it is necessary to override the protected \$entityClassName in the extended class");
        }
    }

    /**
     * @throws NotFoundExceptionInterface when EnityManager not exist
     * @return EntityManagerInterface
     */
    public function getEntityManager()   {
        return $this->entityManager;
    }

    /**
     * @return T 
     * @throws EntityNotRegisteredException
     */
    public function getEntity() {
        if(!$this->entity) throw new EntityNotRegisteredException;
        return $this->entity;
    }

    function remove() : void{
        $this->getEntityManager()->remove($this->entity);
        $this->getEntityManager()->flush();
    }

    function getEntityServiceProvider() : EntityServiceProvider{
        return $this->container->get(EntityServiceProvider::class);
    }
}