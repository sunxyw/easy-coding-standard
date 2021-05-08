<?php

namespace Symplify\CodingStandard\TokenAnalyzer;

use ECSPrefix20210508\Nette\Utils\Strings;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
final class NewlineAnalyzer
{
    /**
     * @param Tokens<Token> $tokens
     * @param int $i
     * @return bool
     */
    public function doesContentBeforeBracketRequireNewline(\PhpCsFixer\Tokenizer\Tokens $tokens, $i)
    {
        $previousMeaningfulTokenPosition = $tokens->getPrevNonWhitespace($i);
        if ($previousMeaningfulTokenPosition === null) {
            return \false;
        }
        $previousToken = $tokens[$previousMeaningfulTokenPosition];
        if (!$previousToken->isGivenKind(\T_STRING)) {
            return \false;
        }
        $previousPreviousMeaningfulTokenPosition = $tokens->getPrevNonWhitespace($previousMeaningfulTokenPosition);
        if ($previousPreviousMeaningfulTokenPosition === null) {
            return \false;
        }
        $previousPreviousToken = $tokens[$previousPreviousMeaningfulTokenPosition];
        if ($previousPreviousToken->getContent() === '{') {
            return \true;
        }
        // is a function
        return $previousPreviousToken->isGivenKind([\T_RETURN, \T_DOUBLE_COLON, T_OPEN_CURLY_BRACKET]);
    }
    /**
     * @return bool
     */
    public function isNewlineToken(\PhpCsFixer\Tokenizer\Token $currentToken)
    {
        if (!$currentToken->isWhitespace()) {
            return \false;
        }
        return \ECSPrefix20210508\Nette\Utils\Strings::contains($currentToken->getContent(), "\n");
    }
}
