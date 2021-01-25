<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Processor\BatchAction;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorInterface;
use Ergonode\Product\Domain\Command\Attribute\ChangeProductAttributesCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

class ProductChangeAttributeBatchActionProcessor implements BatchActionProcessorInterface
{
    private const TYPE = 'product_edit';

    private ProductRepositoryInterface $productRepository;

    private AttributeRepositoryInterface $attributeRepository;

    private CommandBusInterface $commandBus;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        CommandBusInterface $commandBus
    ) {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->commandBus = $commandBus;
    }


    public function supports(BatchActionType $type): bool
    {
        return $type->getValue() === self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function process(BatchActionId $id, AggregateId $resourceId, $payload = null): array
    {
        $messages = [];
        $productId = new ProductId($resourceId->getValue());
        $product = $this->productRepository->load($productId);

        if ($product) {
            $attributes = [];
            foreach ($payload as $attribute) {
                $attributeId = new AttributeId($attribute['id']);
                if ($this->attributeRepository->load($attributeId)) {
                    $value = [];
                    foreach ($attribute['values'] as $translation) {
                        //check validation this value
                        $value[$translation['language']] = $translation['value'];
                    }

                    $attributes[$attributeId->getValue()] = $value;
                } else {
                    $messages[] = new BatchActionMessage('Attribute not found', []);
                }
            }
            if (!empty($attributes)) {
                $command = new ChangeProductAttributesCommand($productId, $attributes);

                $this->commandBus->dispatch($command);
            }
        } else {
            $messages[] = new BatchActionMessage('Product not found', []);
        }

        return $messages;
    }
}
