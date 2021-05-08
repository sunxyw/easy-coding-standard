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
namespace PhpCsFixer\Fixer\Alias;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\FixerDefinition\VersionSpecification;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSample;
use PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
/**
 * @author SpacePossum
 */
final class ArrayPushFixer extends \PhpCsFixer\AbstractFixer
{
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
     */
    public function getDefinition()
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition('Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`.', [new \PhpCsFixer\FixerDefinition\VersionSpecificCodeSample("<?php\narray_push(\$x, \$y);\n", new \PhpCsFixer\FixerDefinition\VersionSpecification(70000))], null, 'Risky when the function `array_push` is overridden.');
    }
    /**
     * {@inheritdoc}
     * @return bool
     */
    public function isCandidate(\PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        return \PHP_VERSION_ID >= 70000 && $tokens->isTokenKindFound(\T_STRING) && $tokens->count() > 7;
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
     * @return void
     */
    protected function applyFix(\SplFileInfo $file, \PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        $functionsAnalyzer = new \PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer();
        for ($index = $tokens->count() - 7; $index > 0; --$index) {
            if (!$tokens[$index]->equals([\T_STRING, 'array_push'], \false)) {
                continue;
            }
            if (!$functionsAnalyzer->isGlobalFunctionCall($tokens, $index)) {
                continue;
                // redeclare/override
            }
            // meaningful before must be `<?php`, `{`, `}` or `;`
            $callIndex = $index;
            $index = $tokens->getPrevMeaningfulToken($index);
            $namespaceSeparatorIndex = null;
            if ($tokens[$index]->isGivenKind(\T_NS_SEPARATOR)) {
                $namespaceSeparatorIndex = $index;
                $index = $tokens->getPrevMeaningfulToken($index);
            }
            if (!$tokens[$index]->equalsAny([';', '{', '}', ')', [\T_OPEN_TAG]])) {
                continue;
            }
            // figure out where the arguments list opens
            $openBraceIndex = $tokens->getNextMeaningfulToken($callIndex);
            $blockType = \PhpCsFixer\Tokenizer\Tokens::detectBlockType($tokens[$openBraceIndex]);
            if (null === $blockType || \PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_PARENTHESIS_BRACE !== $blockType['type']) {
                continue;
            }
            // figure out where the arguments list closes
            $closeBraceIndex = $tokens->findBlockEnd(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openBraceIndex);
            // meaningful after `)` must be `;`, `? >` or nothing
            $afterCloseBraceIndex = $tokens->getNextMeaningfulToken($closeBraceIndex);
            if (null !== $afterCloseBraceIndex && !$tokens[$afterCloseBraceIndex]->equalsAny([';', [\T_CLOSE_TAG]])) {
                continue;
            }
            // must have 2 arguments
            // first argument must be a variable (with possibly array indexing etc.),
            // after that nothing meaningful should be there till the next `,` or `)`
            // if `)` than we cannot fix it (it is a single argument call)
            $firstArgumentStop = $this->getFirstArgumentEnd($tokens, $openBraceIndex);
            $firstArgumentStop = $tokens->getNextMeaningfulToken($firstArgumentStop);
            if (!$tokens[$firstArgumentStop]->equals(',')) {
                return;
            }
            // second argument can be about anything but ellipsis, we must make sure there is not
            // a third argument (or more) passed to `array_push`
            $secondArgumentStart = $tokens->getNextMeaningfulToken($firstArgumentStop);
            $secondArgumentStop = $this->getSecondArgumentEnd($tokens, $secondArgumentStart, $closeBraceIndex);
            if (null === $secondArgumentStop) {
                continue;
            }
            // candidate is valid, replace tokens
            $tokens->clearTokenAndMergeSurroundingWhitespace($closeBraceIndex);
            $tokens->clearTokenAndMergeSurroundingWhitespace($firstArgumentStop);
            $tokens->insertAt($firstArgumentStop, [new \PhpCsFixer\Tokenizer\Token('['), new \PhpCsFixer\Tokenizer\Token(']'), new \PhpCsFixer\Tokenizer\Token([\T_WHITESPACE, ' ']), new \PhpCsFixer\Tokenizer\Token('=')]);
            $tokens->clearTokenAndMergeSurroundingWhitespace($openBraceIndex);
            $tokens->clearTokenAndMergeSurroundingWhitespace($callIndex);
            if (null !== $namespaceSeparatorIndex) {
                $tokens->clearTokenAndMergeSurroundingWhitespace($namespaceSeparatorIndex);
            }
        }
    }
    /**
     * @param int $index
     * @return int
     */
    private function getFirstArgumentEnd(\PhpCsFixer\Tokenizer\Tokens $tokens, $index)
    {
        $nextIndex = $tokens->getNextMeaningfulToken($index);
        $nextToken = $tokens[$nextIndex];
        while ($nextToken->equalsAny(['$', '[', '(', [\PhpCsFixer\Tokenizer\CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN], [\PhpCsFixer\Tokenizer\CT::T_DYNAMIC_PROP_BRACE_OPEN], [\PhpCsFixer\Tokenizer\CT::T_DYNAMIC_VAR_BRACE_OPEN], [\PhpCsFixer\Tokenizer\CT::T_NAMESPACE_OPERATOR], [\T_NS_SEPARATOR], [\T_STATIC], [\T_STRING], [\T_VARIABLE]])) {
            $blockType = \PhpCsFixer\Tokenizer\Tokens::detectBlockType($nextToken);
            if (null !== $blockType) {
                $nextIndex = $tokens->findBlockEnd($blockType['type'], $nextIndex);
            }
            $index = $nextIndex;
            $nextIndex = $tokens->getNextMeaningfulToken($nextIndex);
            $nextToken = $tokens[$nextIndex];
        }
        if ($nextToken->isGivenKind(\T_OBJECT_OPERATOR)) {
            return $this->getFirstArgumentEnd($tokens, $nextIndex);
        }
        if ($nextToken->isGivenKind(\T_PAAMAYIM_NEKUDOTAYIM)) {
            return $this->getFirstArgumentEnd($tokens, $tokens->getNextMeaningfulToken($nextIndex));
        }
        return $index;
    }
    /**
     * @param int $endIndex boundary, i.e. tokens index of `)`
     * @return int|null
     * @param int $index
     */
    private function getSecondArgumentEnd(\PhpCsFixer\Tokenizer\Tokens $tokens, $index, $endIndex)
    {
        if ($tokens[$index]->isGivenKind(\T_ELLIPSIS)) {
            return null;
        }
        $index = $tokens->getNextMeaningfulToken($index);
        for (; $index <= $endIndex; ++$index) {
            $blockType = \PhpCsFixer\Tokenizer\Tokens::detectBlockType($tokens[$index]);
            while (null !== $blockType && $blockType['isStart']) {
                $index = $tokens->findBlockEnd($blockType['type'], $index);
                $index = $tokens->getNextMeaningfulToken($index);
                $blockType = \PhpCsFixer\Tokenizer\Tokens::detectBlockType($tokens[$index]);
            }
            if ($tokens[$index]->equals(',') || $tokens[$index]->isGivenKind([\T_YIELD, \T_YIELD_FROM, \T_LOGICAL_AND, \T_LOGICAL_OR, \T_LOGICAL_XOR])) {
                return null;
            }
        }
        return $endIndex;
    }
}
