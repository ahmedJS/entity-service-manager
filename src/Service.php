<?php
namespace vekas\EntityService;

use Attribute;

#[Attribute]
class Service {
    function __construct(
        public $serviceClass
    ) {

    }
}