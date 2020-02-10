<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use Webmozart\Assert\Assert;

/**
 */
class Magento1ProductProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ConverterMapperProvider
     */
    private ConverterMapperProvider $mapperProvider;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @param CommandBusInterface            $commandBus
     * @param ConverterMapperProvider        $mapperProvider
     * @param TransformerRepositoryInterface $transformerRepository
     */
    public function __construct(
        CommandBusInterface $commandBus,
        ConverterMapperProvider $mapperProvider,
        TransformerRepositoryInterface $transformerRepository
    ) {
        $this->commandBus = $commandBus;
        $this->mapperProvider = $mapperProvider;
        $this->transformerRepository = $transformerRepository;
    }

    /**
     * @param Import         $import
     * @param ProductModel[] $products
     * @param Language       $language
     */
    public function process(Import $import, array $products, Language $language): void
    {
        $transformer = $this->transformerRepository->load($import->getTransformerId());
        Assert::notNull($transformer);
        $i = 0;
        $products = $this->getGroupedProducts($products);
        /** @var ProductModel $product */
        foreach ($products['simple'] as $product) {

            $i++;
            $record = $this->transform($product, $transformer);
            $command = new ProcessImportCommand($import->getId(), $i, $record, ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param ProductModel[] $products
     *
     * @return array
     */
    private function getGroupedProducts(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $result[$product->get('default')['_type']] = $product;

        }

        return $result;
    }

    /**
     * @param ProductModel $product
     * @param Transformer  $transformer
     *
     * @return Record
     */
    public function transform(ProductModel $product, Transformer $transformer): Record
    {
        $default = $product->get('default');
        $record = new Record();
        foreach ($transformer->getConverters() as $collection => $converters) {
            /** @var ConverterInterface $converter */
            foreach ($converters as $field => $converter) {
                $mapper = $this->mapperProvider->provide($converter);
                $value = $mapper->map($converter, $default);
                if ($collection === 'values') {
                    $record->setValue($field, $value);
                } else {
                    $record->set($field, $value);
                }
            }
        }

        return $record;
    }
}
