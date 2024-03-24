<?php


namespace Vekas\EntityService\Interfaces;
use Vekas\EntityService\EntityServiceProvider;

interface EntityServiceProviderRegistererInterface {
    function register() : EntityServiceProvider;
}