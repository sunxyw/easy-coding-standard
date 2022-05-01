<?php

declare (strict_types=1);
namespace ECSPrefix20220501;

use PhpCsFixer\Fixer\Alias\EregToPregFixer;
use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixer\Fixer\Alias\SetTypeToCastFixer;
use PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\Comment\CommentToPhpdocFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagOrderFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer;
use PhpCsFixer\Fixer\FunctionNotation\ImplodeCallFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoUnreachableDefaultArgumentValueFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ErrorSuppressionFixer;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer;
use PhpCsFixer\Fixer\LanguageConstruct\NoUnsetOnPropertyFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\LogicalOperatorsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\StringNotation\StringLineEndingFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
return static function (\Symplify\EasyCodingStandard\Config\ECSConfig $ecsConfig) : void {
    $ecsConfig->rule(\PhpCsFixer\Fixer\Comment\CommentToPhpdocFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Alias\EregToPregFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\LanguageConstruct\ErrorSuppressionFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\FunctionNotation\FopenFlagOrderFixer::class);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer::class, ['b_mode' => \false]);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer::class, ['functions' => ['get_called_class', 'get_class', 'get_class_this', 'php_sapi_name', 'phpversion', 'pi']]);
    $ecsConfig->rule(\PhpCsFixer\Fixer\FunctionNotation\ImplodeCallFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Operator\LogicalOperatorsFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer::class);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer::class, ['fix_built_in' => \false, 'include' => ['DIRECTORY_SEPARATOR', 'PHP_SAPI', 'PHP_VERSION_ID'], 'scope' => 'namespaced']);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer::class, ['include' => ['@compiler_optimized'], 'scope' => 'namespaced', 'strict' => \true]);
    $ecsConfig->ruleWithConfiguration(\PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer::class, ['sets' => ['@all']]);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\FunctionNotation\NoUnreachableDefaultArgumentValueFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\LanguageConstruct\NoUnsetOnPropertyFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Alias\SetTypeToCastFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Strict\StrictComparisonFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Strict\StrictParamFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\StringNotation\StringLineEndingFixer::class);
};
