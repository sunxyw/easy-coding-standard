<?php

declare (strict_types=1);
namespace ECSPrefix202301\Symplify\EasyParallel;

use ECSPrefix202301\Fidry\CpuCoreCounter\CpuCoreCounter;
use ECSPrefix202301\Fidry\CpuCoreCounter\NumberOfCpuCoreNotFound;
final class CpuCoreCountProvider
{
    /**
     * @var int
     */
    private const DEFAULT_CORE_COUNT = 2;
    public function provide() : int
    {
        try {
            return (new CpuCoreCounter())->getCount();
        } catch (NumberOfCpuCoreNotFound $exception) {
            return self::DEFAULT_CORE_COUNT;
        }
    }
}
