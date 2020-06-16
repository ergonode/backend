<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Importer\Infrastructure\Action\Builder\ProductImportBuilderInterface;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Product\Domain\Command\Create\CreateSimpleProductCommand;
use Ergonode\Product\Domain\Command\Update\UpdateSimpleProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

/**
 */
class SimpleProductImportAction implements ImportActionInterface
{
    public const TYPE = 'SIMPLE-PRODUCT';

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ProductImportBuilderInterface ...$builders
     */
    private array $builders;

    /**
     * @param ProductQueryInterface                 $productQuery
     * @param CommandBusInterface                   $commandBus
     * @param array|ProductImportBuilderInterface[] $builders
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus,
        $builders
    ) {
        $this->productQuery = $productQuery;
        $this->commandBus = $commandBus;
        $this->builders = $builders;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $sku = $record->get('sku') ? new Sku($record->get('sku')) : null;

        Assert::notNull($sku, 'product import required "sku" field not exists');

        $importedProduct = new ImportedProduct($sku->getValue());

        foreach ($this->builders as $builder) {
            $importedProduct = $builder->build($importedProduct, $record);
        }

        $productId = $this->productQuery->findProductIdBySku($sku);
        $templateId = new TemplateId($importedProduct->template);

        if (!$productId) {
            $command = new CreateSimpleProductCommand(
                ProductId::generate(),
                $sku,
                $templateId,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
        } else {
            $command = new UpdateSimpleProductCommand(
                $productId,
                $templateId,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
        }

        $this->commandBus->dispatch($command, true);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
