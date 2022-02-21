<?php

// scoper-autoload.php @generated by PhpScoper

$loader = require_once __DIR__.'/autoload.php';

// Aliases for the whitelisted classes. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#class-whitelisting
if (!class_exists('AutoloadIncluder', false) && !interface_exists('AutoloadIncluder', false) && !trait_exists('AutoloadIncluder', false)) {
    spl_autoload_call('ECSPrefix20220221\AutoloadIncluder');
}
if (!class_exists('ComposerAutoloaderInitc510d79fbaf2abc9f1cd63ea5e508f9d', false) && !interface_exists('ComposerAutoloaderInitc510d79fbaf2abc9f1cd63ea5e508f9d', false) && !trait_exists('ComposerAutoloaderInitc510d79fbaf2abc9f1cd63ea5e508f9d', false)) {
    spl_autoload_call('ECSPrefix20220221\ComposerAutoloaderInitc510d79fbaf2abc9f1cd63ea5e508f9d');
}
if (!class_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false) && !interface_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false) && !trait_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator', false)) {
    spl_autoload_call('ECSPrefix20220221\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator');
}
if (!class_exists('Normalizer', false) && !interface_exists('Normalizer', false) && !trait_exists('Normalizer', false)) {
    spl_autoload_call('ECSPrefix20220221\Normalizer');
}
if (!class_exists('Attribute', false) && !interface_exists('Attribute', false) && !trait_exists('Attribute', false)) {
    spl_autoload_call('ECSPrefix20220221\Attribute');
}
if (!class_exists('Stringable', false) && !interface_exists('Stringable', false) && !trait_exists('Stringable', false)) {
    spl_autoload_call('ECSPrefix20220221\Stringable');
}
if (!class_exists('UnhandledMatchError', false) && !interface_exists('UnhandledMatchError', false) && !trait_exists('UnhandledMatchError', false)) {
    spl_autoload_call('ECSPrefix20220221\UnhandledMatchError');
}
if (!class_exists('ValueError', false) && !interface_exists('ValueError', false) && !trait_exists('ValueError', false)) {
    spl_autoload_call('ECSPrefix20220221\ValueError');
}
if (!class_exists('ReturnTypeWillChange', false) && !interface_exists('ReturnTypeWillChange', false) && !trait_exists('ReturnTypeWillChange', false)) {
    spl_autoload_call('ECSPrefix20220221\ReturnTypeWillChange');
}
if (!class_exists('Symplify\SmartFileSystem\SmartFileInfo', false) && !interface_exists('Symplify\SmartFileSystem\SmartFileInfo', false) && !trait_exists('Symplify\SmartFileSystem\SmartFileInfo', false)) {
    spl_autoload_call('ECSPrefix20220221\Symplify\SmartFileSystem\SmartFileInfo');
}

// Functions whitelisting. For more information see:
// https://github.com/humbug/php-scoper/blob/master/README.md#functions-whitelisting
if (!function_exists('composerRequirec510d79fbaf2abc9f1cd63ea5e508f9d')) {
    function composerRequirec510d79fbaf2abc9f1cd63ea5e508f9d() {
        return \ECSPrefix20220221\composerRequirec510d79fbaf2abc9f1cd63ea5e508f9d(...func_get_args());
    }
}
if (!function_exists('sample')) {
    function sample() {
        return \ECSPrefix20220221\sample(...func_get_args());
    }
}
if (!function_exists('foo')) {
    function foo() {
        return \ECSPrefix20220221\foo(...func_get_args());
    }
}
if (!function_exists('bar')) {
    function bar() {
        return \ECSPrefix20220221\bar(...func_get_args());
    }
}
if (!function_exists('baz')) {
    function baz() {
        return \ECSPrefix20220221\baz(...func_get_args());
    }
}
if (!function_exists('xyz')) {
    function xyz() {
        return \ECSPrefix20220221\xyz(...func_get_args());
    }
}
if (!function_exists('scanPath')) {
    function scanPath() {
        return \ECSPrefix20220221\scanPath(...func_get_args());
    }
}
if (!function_exists('lintFile')) {
    function lintFile() {
        return \ECSPrefix20220221\lintFile(...func_get_args());
    }
}
if (!function_exists('uv_signal_init')) {
    function uv_signal_init() {
        return \ECSPrefix20220221\uv_signal_init(...func_get_args());
    }
}
if (!function_exists('uv_signal_start')) {
    function uv_signal_start() {
        return \ECSPrefix20220221\uv_signal_start(...func_get_args());
    }
}
if (!function_exists('uv_poll_init_socket')) {
    function uv_poll_init_socket() {
        return \ECSPrefix20220221\uv_poll_init_socket(...func_get_args());
    }
}
if (!function_exists('printPHPCodeSnifferTestOutput')) {
    function printPHPCodeSnifferTestOutput() {
        return \ECSPrefix20220221\printPHPCodeSnifferTestOutput(...func_get_args());
    }
}
if (!function_exists('setproctitle')) {
    function setproctitle() {
        return \ECSPrefix20220221\setproctitle(...func_get_args());
    }
}
if (!function_exists('array_is_list')) {
    function array_is_list() {
        return \ECSPrefix20220221\array_is_list(...func_get_args());
    }
}
if (!function_exists('enum_exists')) {
    function enum_exists() {
        return \ECSPrefix20220221\enum_exists(...func_get_args());
    }
}

return $loader;
