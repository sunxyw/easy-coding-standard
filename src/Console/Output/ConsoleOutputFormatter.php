<?php

declare (strict_types=1);
namespace Symplify\EasyCodingStandard\Console\Output;

use ECSPrefix20211104\Symfony\Component\Console\Command\Command;
use Symplify\EasyCodingStandard\Console\Style\EasyCodingStandardStyle;
use Symplify\EasyCodingStandard\Contract\Console\Output\OutputFormatterInterface;
use Symplify\EasyCodingStandard\ValueObject\Configuration;
use Symplify\EasyCodingStandard\ValueObject\Error\ErrorAndDiffResult;
use Symplify\EasyCodingStandard\ValueObject\Error\FileDiff;
use Symplify\EasyCodingStandard\ValueObject\Error\SystemError;
final class ConsoleOutputFormatter implements \Symplify\EasyCodingStandard\Contract\Console\Output\OutputFormatterInterface
{
    /**
     * @var string
     */
    public const NAME = 'console';
    /**
     * @var \Symplify\EasyCodingStandard\Console\Style\EasyCodingStandardStyle
     */
    private $easyCodingStandardStyle;
    public function __construct(\Symplify\EasyCodingStandard\Console\Style\EasyCodingStandardStyle $easyCodingStandardStyle)
    {
        $this->easyCodingStandardStyle = $easyCodingStandardStyle;
    }
    /**
     * @param \Symplify\EasyCodingStandard\ValueObject\Error\ErrorAndDiffResult $errorAndDiffResult
     * @param \Symplify\EasyCodingStandard\ValueObject\Configuration $configuration
     */
    public function report($errorAndDiffResult, $configuration) : int
    {
        $this->reportFileDiffs($errorAndDiffResult->getFileDiffs());
        $this->easyCodingStandardStyle->newLine(1);
        if ($errorAndDiffResult->getErrorCount() === 0 && $errorAndDiffResult->getFileDiffsCount() === 0) {
            $this->easyCodingStandardStyle->success('No errors found. Great job - your code is shiny in style!');
            return \ECSPrefix20211104\Symfony\Component\Console\Command\Command::SUCCESS;
        }
        $this->easyCodingStandardStyle->newLine();
        return $configuration->isFixer() ? $this->printAfterFixerStatus($errorAndDiffResult, $configuration) : $this->printNoFixerStatus($errorAndDiffResult, $configuration);
    }
    public function getName() : string
    {
        return self::NAME;
    }
    /**
     * @param FileDiff[] $fileDiffs
     */
    private function reportFileDiffs(array $fileDiffs) : void
    {
        if ($fileDiffs === []) {
            return;
        }
        $this->easyCodingStandardStyle->newLine(1);
        $i = 1;
        foreach ($fileDiffs as $fileDiff) {
            $this->easyCodingStandardStyle->newLine(2);
            $boldNumberedMessage = \sprintf('<options=bold>%d) %s</>', $i, $fileDiff->getRelativeFilePath());
            $this->easyCodingStandardStyle->writeln($boldNumberedMessage);
            ++$i;
            $this->easyCodingStandardStyle->newLine();
            $this->easyCodingStandardStyle->writeln($fileDiff->getDiffConsoleFormatted());
            $this->easyCodingStandardStyle->newLine();
            $this->easyCodingStandardStyle->writeln('<options=underscore>Applied checkers:</>');
            $this->easyCodingStandardStyle->newLine();
            $this->easyCodingStandardStyle->listing($fileDiff->getAppliedCheckers());
        }
    }
    private function printAfterFixerStatus(\Symplify\EasyCodingStandard\ValueObject\Error\ErrorAndDiffResult $errorAndDiffResult, \Symplify\EasyCodingStandard\ValueObject\Configuration $configuration) : int
    {
        if ($configuration->shouldShowErrorTable()) {
            $this->easyCodingStandardStyle->printErrors($errorAndDiffResult->getErrors());
        }
        if ($errorAndDiffResult->getErrorCount() === 0) {
            $successMessage = \sprintf('%d error%s successfully fixed and no other errors found!', $errorAndDiffResult->getFileDiffsCount(), $errorAndDiffResult->getFileDiffsCount() === 1 ? '' : 's');
            $this->easyCodingStandardStyle->success($successMessage);
            return \ECSPrefix20211104\Symfony\Component\Console\Command\Command::SUCCESS;
        }
        $this->printErrorMessageFromErrorCounts($errorAndDiffResult->getCodingStandardErrorCount(), $errorAndDiffResult->getFileDiffsCount(), $configuration);
        return \ECSPrefix20211104\Symfony\Component\Console\Command\Command::FAILURE;
    }
    private function printNoFixerStatus(\Symplify\EasyCodingStandard\ValueObject\Error\ErrorAndDiffResult $errorAndDiffResult, \Symplify\EasyCodingStandard\ValueObject\Configuration $configuration) : int
    {
        if ($configuration->shouldShowErrorTable()) {
            $errors = $errorAndDiffResult->getErrors();
            if ($errors !== []) {
                $this->easyCodingStandardStyle->newLine();
                $this->easyCodingStandardStyle->printErrors($errors);
            }
        }
        $systemErrors = $errorAndDiffResult->getSystemErrors();
        foreach ($systemErrors as $systemError) {
            $this->easyCodingStandardStyle->newLine();
            if ($systemError instanceof \Symplify\EasyCodingStandard\ValueObject\Error\SystemError) {
                $this->easyCodingStandardStyle->error($systemError->getMessage() . ' in ' . $systemError->getFileWithLine());
            } else {
                $this->easyCodingStandardStyle->error($systemError);
            }
        }
        $this->printErrorMessageFromErrorCounts($errorAndDiffResult->getCodingStandardErrorCount(), $errorAndDiffResult->getFileDiffsCount(), $configuration);
        return \ECSPrefix20211104\Symfony\Component\Console\Command\Command::FAILURE;
    }
    private function printErrorMessageFromErrorCounts(int $codingStandardErrorCount, int $fileDiffsCount, \Symplify\EasyCodingStandard\ValueObject\Configuration $configuration) : void
    {
        if ($codingStandardErrorCount !== 0) {
            $errorMessage = \sprintf('Found %d error%s that need%s to be fixed manually.', $codingStandardErrorCount, $codingStandardErrorCount === 1 ? '' : 's', $codingStandardErrorCount === 1 ? 's' : '');
            $this->easyCodingStandardStyle->error($errorMessage);
        }
        if ($fileDiffsCount === 0) {
            return;
        }
        if ($configuration->isFixer()) {
            return;
        }
        $fixableMessage = \sprintf('%s%d %s fixable! Just add "--fix" to console command and rerun to apply.', $codingStandardErrorCount !== 0 ? 'Good news is that ' : '', $fileDiffsCount, $fileDiffsCount === 1 ? 'error is' : 'errors are');
        $this->easyCodingStandardStyle->warning($fixableMessage);
    }
}
