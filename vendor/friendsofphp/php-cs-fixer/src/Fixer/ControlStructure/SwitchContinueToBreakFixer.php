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
namespace PhpCsFixer\Fixer\ControlStructure;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
final class SwitchContinueToBreakFixer extends \PhpCsFixer\AbstractFixer
{
    private $switchLevels = [];
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
     */
    public function getDefinition()
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition('Switch case must not be ended with `continue` but with `break`.', [new \PhpCsFixer\FixerDefinition\CodeSample('<?php
switch ($foo) {
    case 1:
        continue;
}
'), new \PhpCsFixer\FixerDefinition\CodeSample('<?php
switch ($foo) {
    case 1:
        while($bar) {
            do {
                continue 3;
            } while(false);

            if ($foo + 1 > 3) {
                continue;
            }

            continue 2;
        }
}
')]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after NoAlternativeSyntaxFixer.
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
        return $tokens->isAllTokenKindsFound([\T_SWITCH, \T_CONTINUE, \T_LNUMBER]) && !$tokens->hasAlternativeSyntax();
    }
    /**
     * {@inheritdoc}
     * @return void
     */
    protected function applyFix(\SplFileInfo $file, \PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        $count = \count($tokens);
        for ($index = 1; $index < $count - 1; ++$index) {
            $index = $this->doFix($tokens, $index, 0, \false);
        }
    }
    /**
     * @param int $depth >= 0
     * @param int $index
     * @param bool $isInSwitch
     * @return int
     */
    private function doFix(\PhpCsFixer\Tokenizer\Tokens $tokens, $index, $depth, $isInSwitch)
    {
        $index = (int) $index;
        $depth = (int) $depth;
        $isInSwitch = (bool) $isInSwitch;
        $token = $tokens[$index];
        if ($token->isGivenKind([\T_FOREACH, \T_FOR, \T_WHILE])) {
            // go to first `(`, go to its close ')', go to first of '{', ';', '? >'
            $index = $tokens->getNextTokenOfKind($index, ['(']);
            $index = $tokens->getNextTokenOfKind($index, [')']);
            $index = $tokens->getNextTokenOfKind($index, ['{', ';', [\T_CLOSE_TAG]]);
            if (!$tokens[$index]->equals('{')) {
                return $index;
            }
            return $this->fixInLoop($tokens, $index, $depth + 1);
        }
        if ($token->isGivenKind(\T_DO)) {
            return $this->fixInLoop($tokens, $tokens->getNextTokenOfKind($index, ['{']), $depth + 1);
        }
        if ($token->isGivenKind(\T_SWITCH)) {
            return $this->fixInSwitch($tokens, $index, $depth + 1);
        }
        if ($token->isGivenKind(\T_CONTINUE)) {
            return $this->fixContinueWhenActsAsBreak($tokens, $index, $isInSwitch, $depth);
        }
        return $index;
    }
    /**
     * @param int $switchIndex
     * @param int $depth
     * @return int
     */
    private function fixInSwitch(\PhpCsFixer\Tokenizer\Tokens $tokens, $switchIndex, $depth)
    {
        $switchIndex = (int) $switchIndex;
        $depth = (int) $depth;
        $this->switchLevels[] = $depth;
        // figure out where the switch starts
        $openIndex = $tokens->getNextTokenOfKind($switchIndex, ['{']);
        // figure out where the switch ends
        $closeIndex = $tokens->findBlockEnd(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_CURLY_BRACE, $openIndex);
        for ($index = $openIndex + 1; $index < $closeIndex; ++$index) {
            $index = $this->doFix($tokens, $index, $depth, \true);
        }
        \array_pop($this->switchLevels);
        return $closeIndex;
    }
    /**
     * @param int $openIndex
     * @param int $depth
     * @return int
     */
    private function fixInLoop(\PhpCsFixer\Tokenizer\Tokens $tokens, $openIndex, $depth)
    {
        $openIndex = (int) $openIndex;
        $depth = (int) $depth;
        $openCount = 1;
        do {
            ++$openIndex;
            $token = $tokens[$openIndex];
            if ($token->equals('{')) {
                ++$openCount;
                continue;
            }
            if ($token->equals('}')) {
                --$openCount;
                if (0 === $openCount) {
                    break;
                }
                continue;
            }
            $openIndex = $this->doFix($tokens, $openIndex, $depth, \false);
        } while (\true);
        return $openIndex;
    }
    /**
     * @param int $continueIndex
     * @param bool $isInSwitch
     * @param int $depth
     * @return int
     */
    private function fixContinueWhenActsAsBreak(\PhpCsFixer\Tokenizer\Tokens $tokens, $continueIndex, $isInSwitch, $depth)
    {
        $continueIndex = (int) $continueIndex;
        $isInSwitch = (bool) $isInSwitch;
        $depth = (int) $depth;
        $followingContinueIndex = $tokens->getNextMeaningfulToken($continueIndex);
        $followingContinueToken = $tokens[$followingContinueIndex];
        if ($isInSwitch && $followingContinueToken->equals(';')) {
            $this->replaceContinueWithBreakToken($tokens, $continueIndex);
            // short continue 1 notation
            return $followingContinueIndex;
        }
        if (!$followingContinueToken->isGivenKind(\T_LNUMBER)) {
            return $followingContinueIndex;
        }
        $afterFollowingContinueIndex = $tokens->getNextMeaningfulToken($followingContinueIndex);
        if (!$tokens[$afterFollowingContinueIndex]->equals(';')) {
            return $afterFollowingContinueIndex;
            // if next not is `;` return without fixing, for example `continue 1 ? ><?php + $a;`
        }
        // check if continue {jump} targets a switch statement and if so fix it
        $jump = $followingContinueToken->getContent();
        $jump = \str_replace('_', '', $jump);
        // support for numeric_literal_separator
        if (\strlen($jump) > 2 && 'x' === $jump[1]) {
            $jump = \hexdec($jump);
            // hexadecimal - 0x1
        } elseif (\strlen($jump) > 2 && 'b' === $jump[1]) {
            $jump = \bindec($jump);
            // binary - 0b1
        } elseif (\strlen($jump) > 1 && '0' === $jump[0]) {
            $jump = \octdec($jump);
            // octal 01
        } elseif (1 === \PhpCsFixer\Preg::match('#^\\d+$#', $jump)) {
            // positive int
            $jump = (float) $jump;
            // cast to float, might be a number bigger than PHP max. int value
        } else {
            return $afterFollowingContinueIndex;
            // cannot process value, ignore
        }
        if ($jump > \PHP_INT_MAX) {
            return $afterFollowingContinueIndex;
            // cannot process value, ignore
        }
        $jump = (int) $jump;
        if ($isInSwitch && (1 === $jump || 0 === $jump)) {
            $this->replaceContinueWithBreakToken($tokens, $continueIndex);
            // long continue 0/1 notation
            return $afterFollowingContinueIndex;
        }
        $jumpDestination = $depth - $jump + 1;
        if (\in_array($jumpDestination, $this->switchLevels, \true)) {
            $this->replaceContinueWithBreakToken($tokens, $continueIndex);
            return $afterFollowingContinueIndex;
        }
        return $afterFollowingContinueIndex;
    }
    /**
     * @return void
     * @param int $index
     */
    private function replaceContinueWithBreakToken(\PhpCsFixer\Tokenizer\Tokens $tokens, $index)
    {
        $index = (int) $index;
        $tokens[$index] = new \PhpCsFixer\Tokenizer\Token([\T_BREAK, 'break']);
    }
}
