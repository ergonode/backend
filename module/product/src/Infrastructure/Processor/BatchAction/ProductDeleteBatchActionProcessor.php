<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Processor\BatchAction;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorInterface;

class ProductDeleteBatchActionProcessor implements BatchActionProcessorInterface
{
    private const TYPE = 'product_delete';

    private ProductRepositoryInterface $repository;

    private RelationshipsResolverInterface $resolver;

    public function __construct(ProductRepositoryInterface $repository, RelationshipsResolverInterface $resolver)
    {
        $this->repository = $repository;
        $this->resolver = $resolver;
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
        $product = $this->repository->load($productId);
        if ($product) {
            $relationships = $this->resolver->resolve($product->getId());

            if (null !== $relationships) {
                foreach ($relationships as $group) {
                    $messages[] = $this->createMessage($group);
                }
            }
        } else {
            $messages[] = new BatchActionMessage('Product not found', []);
        }

        if (empty($messages)) {
            $this->repository->delete($product);
        }

        return $messages;
    }

    private function createMessage(RelationshipGroup $group): BatchActionMessage
    {
        $relations = [];
        foreach ($group->getRelations() as $relation) {
            $relations[] = $relation->getValue();
        }

        return new BatchActionMessage($group->getMessage(), ['{relations}' => implode(',', $relations)]);
    }
}
