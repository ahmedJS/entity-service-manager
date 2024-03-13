<?php
namespace Vekas\EntityService\Exceptions;

class EntityNotExistException extends \Exception {

    private $entityName;
    function __construct($entityName) {
        $this->entityName = $entityName;
        $this->code = 404;
        $this->message = "the field $entityName was not set";
    }
}