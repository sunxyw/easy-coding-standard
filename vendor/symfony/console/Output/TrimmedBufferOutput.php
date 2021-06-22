<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20210622\Symfony\Component\Console\Output;

use ECSPrefix20210622\Symfony\Component\Console\Exception\InvalidArgumentException;
use ECSPrefix20210622\Symfony\Component\Console\Formatter\OutputFormatterInterface;
/**
 * A BufferedOutput that keeps only the last N chars.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class TrimmedBufferOutput extends \ECSPrefix20210622\Symfony\Component\Console\Output\Output
{
    private $maxLength;
    private $buffer = '';
    /**
     * @param int|null $verbosity
     */
    public function __construct(int $maxLength, $verbosity = self::VERBOSITY_NORMAL, bool $decorated = \false, \ECSPrefix20210622\Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter = null)
    {
        if ($maxLength <= 0) {
            throw new \ECSPrefix20210622\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('"%s()" expects a strictly positive maxLength. Got %d.', __METHOD__, $maxLength));
        }
        parent::__construct($verbosity, $decorated, $formatter);
        $this->maxLength = $maxLength;
    }
    /**
     * Empties buffer and returns its content.
     *
     * @return string
     */
    public function fetch()
    {
        $content = $this->buffer;
        $this->buffer = '';
        return $content;
    }
    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline)
    {
        $this->buffer .= $message;
        if ($newline) {
            $this->buffer .= \PHP_EOL;
        }
        $this->buffer = \substr($this->buffer, 0 - $this->maxLength);
    }
}
