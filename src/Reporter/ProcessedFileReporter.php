<?php

namespace Symplify\EasyCodingStandard\Reporter;

use Symplify\EasyCodingStandard\Configuration\Configuration;
use Symplify\EasyCodingStandard\Console\Output\OutputFormatterCollector;
use Symplify\EasyCodingStandard\Error\ErrorAndDiffResultFactory;
final class ProcessedFileReporter
{
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var OutputFormatterCollector
     */
    private $outputFormatterCollector;
    /**
     * @var ErrorAndDiffResultFactory
     */
    private $errorAndDiffResultFactory;
    public function __construct(\Symplify\EasyCodingStandard\Configuration\Configuration $configuration, \Symplify\EasyCodingStandard\Console\Output\OutputFormatterCollector $outputFormatterCollector, \Symplify\EasyCodingStandard\Error\ErrorAndDiffResultFactory $errorAndDiffResultFactory)
    {
        $this->configuration = $configuration;
        $this->outputFormatterCollector = $outputFormatterCollector;
        $this->errorAndDiffResultFactory = $errorAndDiffResultFactory;
    }
    /**
     * @param int $processedFileCount
     * @return int
     */
    public function report($processedFileCount)
    {
        $outputFormat = $this->configuration->getOutputFormat();
        $outputFormatter = $this->outputFormatterCollector->getByName($outputFormat);
        $errorAndDiffResult = $this->errorAndDiffResultFactory->create();
        return $outputFormatter->report($errorAndDiffResult, $processedFileCount);
    }
}
