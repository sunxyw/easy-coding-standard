<?php

declare (strict_types=1);
namespace ECSPrefix20210917\Symplify\Skipper\SkipCriteriaResolver;

use ECSPrefix20210917\Symplify\PackageBuilder\Parameter\ParameterProvider;
use ECSPrefix20210917\Symplify\Skipper\ValueObject\Option;
final class SkippedClassAndCodesResolver
{
    /**
     * @var array<string, string[]|null>
     */
    private $skippedClassAndCodes = [];
    /**
     * @var \Symplify\PackageBuilder\Parameter\ParameterProvider
     */
    private $parameterProvider;
    public function __construct(\ECSPrefix20210917\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->parameterProvider = $parameterProvider;
    }
    /**
     * @return array<string, string[]|null>
     */
    public function resolve() : array
    {
        if ($this->skippedClassAndCodes !== []) {
            return $this->skippedClassAndCodes;
        }
        $skip = $this->parameterProvider->provideArrayParameter(\ECSPrefix20210917\Symplify\Skipper\ValueObject\Option::SKIP);
        foreach ($skip as $key => $value) {
            // e.g. [SomeClass::class] → shift values to [SomeClass::class => null]
            if (\is_int($key)) {
                $key = $value;
                $value = null;
            }
            if (\substr_count($key, '.') !== 1) {
                continue;
            }
            $this->skippedClassAndCodes[$key] = $value;
        }
        return $this->skippedClassAndCodes;
    }
}
