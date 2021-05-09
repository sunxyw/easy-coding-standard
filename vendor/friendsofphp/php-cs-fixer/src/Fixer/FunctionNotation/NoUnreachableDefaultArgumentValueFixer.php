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
namespace PhpCsFixer\Fixer\FunctionNotation;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Mark Scherer
 * @author Lucas Manzke <lmanzke@outlook.com>
 * @author Gregor Harlan <gharlan@web.de>
 */
final class NoUnreachableDefaultArgumentValueFixer extends \PhpCsFixer\AbstractFixer
{
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
     */
    public function getDefinition()
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition('In function arguments there must not be arguments with default values before non-default ones.', [new \PhpCsFixer\FixerDefinition\CodeSample('<?php
function example($foo = "two words", $bar) {}
')], null, 'Modifies the signature of functions; therefore risky when using systems (such as some Symfony components) that rely on those (for example through reflection).');
    }
    /**
     * {@inheritdoc}
     *
     * Must run after NullableTypeDeclarationForDefaultNullValueFixer.
     * @return int
     */
    public function getPriority()
    {
        return 0;
    }
    /**
     * {@inheritdoc}
     * @return bool
     */
    public function isCandidate(\PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        if (\PHP_VERSION_ID >= 70400 && $tokens->isTokenKindFound(\T_FN)) {
            return \true;
        }
        return $tokens->isTokenKindFound(\T_FUNCTION);
    }
    /**
     * {@inheritdoc}
     * @return bool
     */
    public function isRisky()
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     * @return void
     */
    protected function applyFix(\SplFileInfo $file, \PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        for ($i = 0, $l = $tokens->count(); $i < $l; ++$i) {
            if (!$tokens[$i]->isGivenKind(\T_FUNCTION) && (\PHP_VERSION_ID < 70400 || !$tokens[$i]->isGivenKind(\T_FN))) {
                continue;
            }
            $startIndex = $tokens->getNextTokenOfKind($i, ['(']);
            $i = $tokens->findBlockEnd(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $startIndex);
            $this->fixFunctionDefinition($tokens, $startIndex, $i);
        }
    }
    /**
     * @return void
     * @param int $startIndex
     * @param int $endIndex
     */
    private function fixFunctionDefinition(\PhpCsFixer\Tokenizer\Tokens $tokens, $startIndex, $endIndex)
    {
        $startIndex = (int) $startIndex;
        $endIndex = (int) $endIndex;
        $lastArgumentIndex = $this->getLastNonDefaultArgumentIndex($tokens, $startIndex, $endIndex);
        if (!$lastArgumentIndex) {
            return;
        }
        for ($i = $lastArgumentIndex; $i > $startIndex; --$i) {
            $token = $tokens[$i];
            if ($token->isGivenKind(\T_VARIABLE)) {
                $lastArgumentIndex = $i;
                continue;
            }
            if (!$token->equals('=') || $this->isNonNullableTypehintedNullableVariable($tokens, $i)) {
                continue;
            }
            $endIndex = $tokens->getPrevTokenOfKind($lastArgumentIndex, [',']);
            $endIndex = $tokens->getPrevMeaningfulToken($endIndex);
            $this->removeDefaultArgument($tokens, $i, $endIndex);
        }
    }
    /**
     * @return int|null
     * @param int $startIndex
     * @param int $endIndex
     */
    private function getLastNonDefaultArgumentIndex(\PhpCsFixer\Tokenizer\Tokens $tokens, $startIndex, $endIndex)
    {
        $startIndex = (int) $startIndex;
        $endIndex = (int) $endIndex;
        for ($i = $endIndex - 1; $i > $startIndex; --$i) {
            $token = $tokens[$i];
            if ($token->equals('=')) {
                $i = $tokens->getPrevMeaningfulToken($i);
                continue;
            }
            if ($token->isGivenKind(\T_VARIABLE) && !$this->isEllipsis($tokens, $i)) {
                return $i;
            }
        }
        return null;
    }
    /**
     * @param int $variableIndex
     * @return bool
     */
    private function isEllipsis(\PhpCsFixer\Tokenizer\Tokens $tokens, $variableIndex)
    {
        $variableIndex = (int) $variableIndex;
        return $tokens[$tokens->getPrevMeaningfulToken($variableIndex)]->isGivenKind(\T_ELLIPSIS);
    }
    /**
     * @return void
     * @param int $startIndex
     * @param int $endIndex
     */
    private function removeDefaultArgument(\PhpCsFixer\Tokenizer\Tokens $tokens, $startIndex, $endIndex)
    {
        $startIndex = (int) $startIndex;
        $endIndex = (int) $endIndex;
        for ($i = $startIndex; $i <= $endIndex;) {
            $tokens->clearTokenAndMergeSurroundingWhitespace($i);
            $this->clearWhitespacesBeforeIndex($tokens, $i);
            $i = $tokens->getNextMeaningfulToken($i);
        }
    }
    /**
     * @param int $index Index of "="
     * @return bool
     */
    private function isNonNullableTypehintedNullableVariable(\PhpCsFixer\Tokenizer\Tokens $tokens, $index)
    {
        $index = (int) $index;
        $nextToken = $tokens[$tokens->getNextMeaningfulToken($index)];
        if (!$nextToken->equals([\T_STRING, 'null'], \false)) {
            return \false;
        }
        $variableIndex = $tokens->getPrevMeaningfulToken($index);
        $searchTokens = [',', '(', [\T_STRING], [\PhpCsFixer\Tokenizer\CT::T_ARRAY_TYPEHINT], [\T_CALLABLE]];
        $typehintKinds = [\T_STRING, \PhpCsFixer\Tokenizer\CT::T_ARRAY_TYPEHINT, \T_CALLABLE];
        $prevIndex = $tokens->getPrevTokenOfKind($variableIndex, $searchTokens);
        if (!$tokens[$prevIndex]->isGivenKind($typehintKinds)) {
            return \false;
        }
        return !$tokens[$tokens->getPrevMeaningfulToken($prevIndex)]->isGivenKind(\PhpCsFixer\Tokenizer\CT::T_NULLABLE_TYPE);
    }
    /**
     * @return void
     * @param int $index
     */
    private function clearWhitespacesBeforeIndex(\PhpCsFixer\Tokenizer\Tokens $tokens, $index)
    {
        $index = (int) $index;
        $prevIndex = $tokens->getNonEmptySibling($index, -1);
        if (!$tokens[$prevIndex]->isWhitespace()) {
            return;
        }
        $prevNonWhiteIndex = $tokens->getPrevNonWhitespace($prevIndex);
        if (null === $prevNonWhiteIndex || !$tokens[$prevNonWhiteIndex]->isComment()) {
            $tokens->clearTokenAndMergeSurroundingWhitespace($prevIndex);
        }
    }
}
