<?php

namespace Vekas\EntityService;

abstract class BasicEntity {
    private $id;

    function __construct($id){
        $this->id = $id;
    }
    function getId() {
        return $this->id;
    }
}