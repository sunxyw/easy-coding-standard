<?php

namespace Symplify\CodingStandard\TokenAnalyzer;

use PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
final class DocblockRelatedParamNamesResolver
{
    /**
     * @var FunctionsAnalyzer
     */
    private $functionsAnalyzer;
    /**
     * @var Token[]
     */
    private $functionTokens = [];
    public function __construct(\PhpCsFixer\Tokenizer\Analyzer\FunctionsAnalyzer $functionsAnalyzer)
    {
        $this->functionsAnalyzer = $functionsAnalyzer;
        $this->functionTokens[] = new \PhpCsFixer\Tokenizer\Token([\T_FUNCTION, 'function']);
        // only in PHP 7.4+
        if ($this->doesFnTokenExist()) {
            $this->functionTokens[] = new \PhpCsFixer\Tokenizer\Token([\T_FN, 'fn']);
        }
    }
    /**
     * @return mixed[]
     * @param Tokens<Token> $tokens
     * @param int $docTokenPosition
     */
    public function resolve(\PhpCsFixer\Tokenizer\Tokens $tokens, $docTokenPosition)
    {
        $functionTokenPosition = $tokens->getNextTokenOfKind($docTokenPosition, $this->functionTokens);
        if ($functionTokenPosition === null) {
            return [];
        }
        /** @var array<string, mixed> $functionArgumentAnalyses */
        $functionArgumentAnalyses = $this->functionsAnalyzer->getFunctionArguments($tokens, $functionTokenPosition);
        return \array_keys($functionArgumentAnalyses);
    }
    /**
     * @return bool
     */
    private function doesFnTokenExist()
    {
        if (!\defined('T_FN')) {
            return \false;
        }
        return \PHP_VERSION_ID >= 70400;
    }
}
