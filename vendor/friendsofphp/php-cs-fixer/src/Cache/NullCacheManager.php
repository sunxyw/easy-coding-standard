<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PhpCsFixer\Cache;

/**
 * @author Andreas Möller <am@localheinz.com>
 *
 * @internal
 */
final class NullCacheManager implements \PhpCsFixer\Cache\CacheManagerInterface
{
    /**
     * @param string $file
     * @param string $fileContent
     * @return bool
     */
    public function needFixing($file, $fileContent)
    {
        $file = (string) $file;
        $fileContent = (string) $fileContent;
        return \true;
    }
    /**
     * @return void
     * @param string $file
     * @param string $fileContent
     */
    public function setFile($file, $fileContent)
    {
    }
}
