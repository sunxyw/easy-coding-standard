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
namespace PhpCsFixer\Fixer\Operator;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * @author SpacePossum
 */
final class ConcatSpaceFixer extends \PhpCsFixer\AbstractFixer implements \PhpCsFixer\Fixer\ConfigurableFixerInterface
{
    private $fixCallback;
    /**
     * {@inheritdoc}
     * @return void
     */
    public function configure(array $configuration)
    {
        parent::configure($configuration);
        if ('one' === $this->configuration['spacing']) {
            $this->fixCallback = 'fixConcatenationToSingleSpace';
        } else {
            $this->fixCallback = 'fixConcatenationToNoSpace';
        }
    }
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
     */
    public function getDefinition()
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition('Concatenation should be spaced according configuration.', [new \PhpCsFixer\FixerDefinition\CodeSample("<?php\n\$foo = 'bar' . 3 . 'baz'.'qux';\n"), new \PhpCsFixer\FixerDefinition\CodeSample("<?php\n\$foo = 'bar' . 3 . 'baz'.'qux';\n", ['spacing' => 'none']), new \PhpCsFixer\FixerDefinition\CodeSample("<?php\n\$foo = 'bar' . 3 . 'baz'.'qux';\n", ['spacing' => 'one'])]);
    }
    /**
     * {@inheritdoc}
     *
     * Must run after SingleLineThrowFixer.
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
        return $tokens->isTokenKindFound('.');
    }
    /**
     * {@inheritdoc}
     * @return void
     */
    protected function applyFix(\SplFileInfo $file, \PhpCsFixer\Tokenizer\Tokens $tokens)
    {
        $callBack = $this->fixCallback;
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            if ($tokens[$index]->equals('.')) {
                $this->{$callBack}($tokens, $index);
            }
        }
    }
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface
     */
    protected function createConfigurationDefinition()
    {
        return new \PhpCsFixer\FixerConfiguration\FixerConfigurationResolver([(new \PhpCsFixer\FixerConfiguration\FixerOptionBuilder('spacing', 'Spacing to apply around concatenation operator.'))->setAllowedValues(['one', 'none'])->setDefault('none')->getOption()]);
    }
    /**
     * @param int $index index of concatenation '.' token
     * @return void
     */
    private function fixConcatenationToNoSpace(\PhpCsFixer\Tokenizer\Tokens $tokens, $index)
    {
        $prevNonWhitespaceToken = $tokens[$tokens->getPrevNonWhitespace($index)];
        if (!$prevNonWhitespaceToken->isGivenKind([\T_LNUMBER, \T_COMMENT, \T_DOC_COMMENT]) || '/*' === \substr($prevNonWhitespaceToken->getContent(), 0, 2)) {
            $tokens->removeLeadingWhitespace($index, " \t");
        }
        if (!$tokens[$tokens->getNextNonWhitespace($index)]->isGivenKind([\T_LNUMBER, \T_COMMENT, \T_DOC_COMMENT])) {
            $tokens->removeTrailingWhitespace($index, " \t");
        }
    }
    /**
     * @param int $index index of concatenation '.' token
     * @return void
     */
    private function fixConcatenationToSingleSpace(\PhpCsFixer\Tokenizer\Tokens $tokens, $index)
    {
        $this->fixWhiteSpaceAroundConcatToken($tokens, $index, 1);
        $this->fixWhiteSpaceAroundConcatToken($tokens, $index, -1);
    }
    /**
     * @param int $index  index of concatenation '.' token
     * @param int $offset 1 or -1
     * @return void
     */
    private function fixWhiteSpaceAroundConcatToken(\PhpCsFixer\Tokenizer\Tokens $tokens, $index, $offset)
    {
        $offsetIndex = $index + $offset;
        if (!$tokens[$offsetIndex]->isWhitespace()) {
            $tokens->insertAt($index + (1 === $offset ?: 0), new \PhpCsFixer\Tokenizer\Token([\T_WHITESPACE, ' ']));
            return;
        }
        if (\false !== \strpos($tokens[$offsetIndex]->getContent(), "\n")) {
            return;
        }
        if ($tokens[$index + $offset * 2]->isComment()) {
            return;
        }
        $tokens[$offsetIndex] = new \PhpCsFixer\Tokenizer\Token([\T_WHITESPACE, ' ']);
    }
}
