<?php

declare (strict_types=1);
namespace ECSPrefix20211208;

use ECSPrefix20211208\Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use ECSPrefix20211208\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use ECSPrefix20211208\Symplify\PackageBuilder\Parameter\ParameterProvider;
use ECSPrefix20211208\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use ECSPrefix20211208\Symplify\SmartFileSystem\FileSystemFilter;
use ECSPrefix20211208\Symplify\SmartFileSystem\FileSystemGuard;
use ECSPrefix20211208\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use ECSPrefix20211208\Symplify\SmartFileSystem\Finder\SmartFinder;
use ECSPrefix20211208\Symplify\SmartFileSystem\SmartFileSystem;
use function ECSPrefix20211208\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    // symfony style
    $services->set(\ECSPrefix20211208\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class);
    $services->set(\ECSPrefix20211208\Symfony\Component\Console\Style\SymfonyStyle::class)->factory([\ECSPrefix20211208\Symfony\Component\DependencyInjection\Loader\Configurator\service(\ECSPrefix20211208\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class), 'create']);
    // filesystem
    $services->set(\ECSPrefix20211208\Symplify\SmartFileSystem\Finder\FinderSanitizer::class);
    $services->set(\ECSPrefix20211208\Symplify\SmartFileSystem\SmartFileSystem::class);
    $services->set(\ECSPrefix20211208\Symplify\SmartFileSystem\Finder\SmartFinder::class);
    $services->set(\ECSPrefix20211208\Symplify\SmartFileSystem\FileSystemGuard::class);
    $services->set(\ECSPrefix20211208\Symplify\SmartFileSystem\FileSystemFilter::class);
    $services->set(\ECSPrefix20211208\Symplify\PackageBuilder\Parameter\ParameterProvider::class)->args([\ECSPrefix20211208\Symfony\Component\DependencyInjection\Loader\Configurator\service('service_container')]);
    $services->set(\ECSPrefix20211208\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
