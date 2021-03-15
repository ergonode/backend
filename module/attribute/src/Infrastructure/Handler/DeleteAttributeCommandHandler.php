<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;

class DeleteAttributeCommandHandler
{
    private AttributeRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        AttributeRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws ExistingRelationshipsException
     */
    public function __invoke(DeleteAttributeCommand $command): void
    {
        $attribute = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $attribute,
            AbstractAttribute::class,
            sprintf('Attribute with ID "%s" not found', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($attribute);
    }
}
