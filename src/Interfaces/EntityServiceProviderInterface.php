<?php

namespace Vekas\EntityService\Interfaces;

interface EntityServiceProviderInterface {
    function provide($class,$id);
}