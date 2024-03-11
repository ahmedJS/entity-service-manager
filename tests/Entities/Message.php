<?php


namespace Vekas\EntityService\Tests\Entities;

use Vekas\EntityService\BasicEntity;

class Message extends BasicEntity {
    function __construct($id) {
        parent::__construct($id);
    }
}