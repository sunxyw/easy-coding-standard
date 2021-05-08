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
namespace PhpCsFixer\Fixer\Strict;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class StrictParamFixer extends \PhpCsFixer\AbstractFixer
{
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
     */
    public function getDefinition()
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition('Functions should be used with `$strict` param set to `true`.', [new \PhpCsFixer\FixerDefinition\CodeSample("<?php\n\$a = array_keys(\$b);\n\$a = array_search(\$b, \$c);\n\$a = base64_decode(\$b);\n\$a = in_array(\$b, \$c);\n\$a = mb_detect_encoding(\$b, \$c);\n")], 'The functions "array_keys", "array_search", "base64_decode", "in_array" and "mb_detect_encoding" should be used with $strict param.', 'Risky when the fixed function is overridden or if the code relies on non-strict usage.');
    }
    /**
     * {@inheritdoc}
     * @return bool
     */
    public function isCandidate(\PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        return $tokens->isTokenKindFound(\T_STRING);
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
     *
     * Must run before NativeFunctionInvocationFixer.
     * @return int
     */
    public function getPriority()
    {
        return 11;
    }
    /**
     * {@inheritdoc}
     * @return void
     */
    protected function applyFix(\SplFileInfo $file, \PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        static $map = null;
        if (null === $map) {
            $trueToken = new \PhpCsFixer\Tokenizer\Token([\T_STRING, 'true']);
            $map = ['array_keys' => [null, null, $trueToken], 'array_search' => [null, null, $trueToken], 'base64_decode' => [null, $trueToken], 'in_array' => [null, null, $trueToken], 'mb_detect_encoding' => [null, [new \PhpCsFixer\Tokenizer\Token([\T_STRING, 'mb_detect_order']), new \PhpCsFixer\Tokenizer\Token('('), new \PhpCsFixer\Tokenizer\Token(')')], $trueToken]];
        }
        for ($index = $tokens->count() - 1; 0 <= $index; --$index) {
            $token = $tokens[$index];
            $nextIndex = $tokens->getNextMeaningfulToken($index);
            if (null !== $nextIndex && !$tokens[$nextIndex]->equals('(')) {
                continue;
            }
            $lowercaseContent = \strtolower($token->getContent());
            if ($token->isGivenKind(\T_STRING) && isset($map[$lowercaseContent])) {
                $this->fixFunction($tokens, $index, $map[$lowercaseContent]);
            }
        }
    }
    /**
     * @return void
     * @param int $functionIndex
     */
    private function fixFunction(\PhpCsFixer\Tokenizer\Tokens $tokens, $functionIndex, array $functionParams)
    {
        $startBraceIndex = $tokens->getNextTokenOfKind($functionIndex, ['(']);
        $endBraceIndex = $tokens->findBlockEnd(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $startBraceIndex);
        $paramsQuantity = 0;
        $expectParam = \true;
        for ($index = $startBraceIndex + 1; $index < $endBraceIndex; ++$index) {
            $token = $tokens[$index];
            if ($expectParam && !$token->isWhitespace() && !$token->isComment()) {
                ++$paramsQuantity;
                $expectParam = \false;
            }
            if ($token->equals('(')) {
                $index = $tokens->findBlockEnd(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index);
                continue;
            }
            if ($token->isGivenKind(\PhpCsFixer\Tokenizer\CT::T_ARRAY_SQUARE_BRACE_OPEN)) {
                $index = $tokens->findBlockEnd(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_ARRAY_SQUARE_BRACE, $index);
                continue;
            }
            if ($token->equals(',')) {
                $expectParam = \true;
                continue;
            }
        }
        $functionParamsQuantity = \count($functionParams);
        if ($paramsQuantity === $functionParamsQuantity) {
            return;
        }
        $tokensToInsert = [];
        for ($i = $paramsQuantity; $i < $functionParamsQuantity; ++$i) {
            // function call do not have all params that are required to set useStrict flag, exit from method!
            if (!$functionParams[$i]) {
                return;
            }
            $tokensToInsert[] = new \PhpCsFixer\Tokenizer\Token(',');
            $tokensToInsert[] = new \PhpCsFixer\Tokenizer\Token([\T_WHITESPACE, ' ']);
            if (!\is_array($functionParams[$i])) {
                $tokensToInsert[] = clone $functionParams[$i];
                continue;
            }
            foreach ($functionParams[$i] as $param) {
                $tokensToInsert[] = clone $param;
            }
        }
        $beforeEndBraceIndex = $tokens->getPrevMeaningfulToken($endBraceIndex);
        if ($tokens[$beforeEndBraceIndex]->equals(',')) {
            \array_shift($tokensToInsert);
            $tokensToInsert[] = new \PhpCsFixer\Tokenizer\Token(',');
        }
        $tokens->insertAt($beforeEndBraceIndex + 1, $tokensToInsert);
    }
}
