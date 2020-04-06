<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Webmozart\Assert\Assert;
use Ergonode\ImporterMagento1\Infrastructure\Reader\Magento1CsvReader;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Application\Controller\Api\NotificationCheckAction;
use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;

/**
 */
class StartMagento1ImportProcess implements SourceImportProcessorInterface
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var Magento1CsvReader
     */
    private Magento1CsvReader $reader;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface              $sourceRepository
     * @param TransformerRepositoryInterface         $transformerRepository
     * @param Magento1CsvReader                      $reader
     * @param CommandBusInterface                    $commandBus
     * @param array|Magento1ProcessorStepInterface[] $steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        Magento1CsvReader $reader,
        CommandBusInterface $commandBus,
        $steps
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->reader = $reader;
        $this->commandBus = $commandBus;
        $this->steps = $steps;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === Magento1CsvSource::TYPE;
    }

    /**
     * @param Import $import
     *
     * @throws \Throwable
     */
    public function start(Import $import): void
    {
        try {
            /** @var Magento1CsvSource $source */
            $source = $this->sourceRepository->load($import->getSourceId());
            Assert::notNull($source);
            $transformer = $this->transformerRepository->load($import->getTransformerId());
            Assert::notNull($transformer);

            $products = $this->reader->read($source, $import, $transformer);

            $result = [];
            foreach ($products as $sku => $product) {
                $result[$sku] = new ProductModel();
                foreach ($product as $code => $version) {
                    $result[$sku]->add($code, $version);
                }
            }

            $count = count($this->steps);
            $i = 0;
            foreach ($this->steps as $step) {
                $i++;
                $steps = new Progress($i, $count);
                $step->process($import, $result, $transformer, $source, $steps);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
