<?php

declare (strict_types=1);
namespace ECSPrefix20211126;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('ECSPrefix20211126\Symplify\EasyParallel\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/ValueObject']);
};
