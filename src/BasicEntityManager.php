<?php

namespace Vekas\EntityService;
use Doctrine\ORM\EntityManagerInterface;

class BasicEntityManager  
{
    protected $entities = [];

    public function persist($entity)
    {
        // Fake persisting logic
        $this->entities[] = $entity;
    }

    public function remove($entity)
    {
        // Fake removal logic
        $key = array_search($entity, $this->entities);
        if ($key !== false) {
            echo "removing entity : ".$entity->getId();
            $this->entities = array_filter($this->entities,function($_entity) use ($entity) {
                return $entity !== $_entity;
            });
        }
    }   

    public function flush()
    {
        // Fake flushing logic
        foreach ($this->entities as $entity) {
            // Fake saving to the database
            // echo "Saving entity to the database: ".($entity)::class."\n";
        }
    }

    // Fake method to simulate finding entities by ID
    public function find($entityClass, $id)
    {
        foreach ($this->entities as $entity) {
            if ($entity instanceof $entityClass && $entity->getId() === $id) {
                return $entity;
            }
        }
        return null;
    }
    
    function addEntity($entity) {
        array_push($this->entities,$entity);
    }

    function getEntities() {
        return $this->entities;
    }
}
