<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Command\Option\DeleteOptionCommand;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;

class DeleteOptionCommandHandler
{
    private OptionRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        OptionRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteOptionCommand $command): void
    {
        $option = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $option,
            AbstractOption::class,
            sprintf('Option with ID "%s" not found', $command->getId())
        );
        $relationships = $this->relationshipsResolver->resolve($command->getId());

        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($option);
    }
}
