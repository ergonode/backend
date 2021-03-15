<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Webmozart\Assert\Assert;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\ImporterMagento1\Infrastructure\Reader\Magento1CsvReader;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

class StartMagento1ImportProcess implements SourceImportProcessorInterface
{
    private SourceRepositoryInterface $sourceRepository;

    private AttributeRepositoryInterface $attributeRepository;

    private Magento1CsvReader $reader;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param Magento1ProcessorStepInterface[] $steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        AttributeRepositoryInterface $attributeRepository,
        Magento1CsvReader $reader,
        array $steps
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->attributeRepository = $attributeRepository;
        $this->reader = $reader;
        $this->steps = $steps;
    }

    public function supported(string $type): bool
    {
        return $type === Magento1CsvSource::TYPE;
    }

    /**
     * @throws ReaderException
     * @throws \ReflectionException
     */
    public function start(Import $import): void
    {
        /** @var Magento1CsvSource $source */
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);

        $attributes = [];
        foreach ($source->getAttributes() as $magentoCode => $attributeId) {
            $attributes[$magentoCode] = $this->attributeRepository->load($attributeId);
        }

        $this->reader->open($import);
        while ($product = $this->reader->read($attributes)) {
            foreach ($this->steps as $step) {
                $step->process($import, $product, $source, $attributes);
            }
        }

        $this->reader->close();
    }
}
