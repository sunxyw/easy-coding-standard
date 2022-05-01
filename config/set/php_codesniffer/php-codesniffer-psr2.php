<?php

declare (strict_types=1);
namespace ECSPrefix20220501;

use PHP_CodeSniffer\Standards\Generic\Sniffs\ControlStructures\InlineControlStructureSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\ByteOrderMarkSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineEndingsSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\DisallowMultipleStatementsSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Functions\FunctionCallArgumentSpacingSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\UpperCaseConstantNameSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseConstantSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseKeywordSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\ScopeIndentSniff;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Functions\ValidDefaultValueSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\PropertyDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ElseIfDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\SwitchDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\ClosingTagSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\EndFileNewlineSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionClosingBraceSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\MethodDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\NamespaceDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\UseDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ControlSignatureSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForEachLoopDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForLoopDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\LowercaseDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationArgumentSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\LowercaseFunctionKeywordsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\MultiLineFunctionDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Scope\MethodScopeSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ScopeClosingBraceSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ScopeKeywordSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
return static function (\Symplify\EasyCodingStandard\Config\ECSConfig $ecsConfig) : void {
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\NamespaceDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\UseDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\PropertyDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\EndFileNewlineSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\ClosingTagSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\SwitchDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ElseIfDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\MethodDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionClosingBraceSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\Files\ByteOrderMarkSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ValidClassNameSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\UpperCaseConstantNameSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\DisallowMultipleStatementsSniff::class);
    $ecsConfig->ruleWithConfiguration(\PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineEndingsSniff::class, ['eolChar' => '\\n']);
    $ecsConfig->ruleWithConfiguration(\PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff::class, ['ignoreBlankLines' => \true]);
    $ecsConfig->ruleWithConfiguration(\PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\ScopeIndentSniff::class, ['ignoreIndentationTokens' => ['T_COMMENT', 'T_DOC_COMMENT_OPEN_TAG']]);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseKeywordSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\LowerCaseConstantSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\Scope\MethodScopeSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ScopeKeywordSpacingSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\LowercaseFunctionKeywordsSniff::class);
    $ecsConfig->ruleWithConfiguration(\PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\FunctionDeclarationArgumentSpacingSniff::class, ['equalsSpacing' => 1]);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\PEAR\Sniffs\Functions\ValidDefaultValueSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\MultiLineFunctionDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\Functions\FunctionCallArgumentSpacingSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ControlSignatureSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\ScopeClosingBraceSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForEachLoopDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForLoopDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\LowercaseDeclarationSniff::class);
    $ecsConfig->rule(\PHP_CodeSniffer\Standards\Generic\Sniffs\ControlStructures\InlineControlStructureSniff::class);
    $ecsConfig->skip([\PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff::class . '.SpacingAfterOpenBrace', \PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff::class . '.SpaceBeforeCloseBrace', \PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff::class . '.LineAfterClose', \PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ControlStructureSpacingSniff::class . '.NoLineAfterClose', \PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff::class . '.OpeningIndent']);
};
