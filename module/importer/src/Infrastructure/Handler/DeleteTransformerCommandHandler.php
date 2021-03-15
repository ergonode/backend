<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Importer\Domain\Command\DeleteTransformerCommand;
use Ergonode\Importer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteTransformerCommandHandler
{
    private TransformerRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        TransformerRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteTransformerCommand $command): void
    {
        $transformer = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $transformer,
            Transformer::class,
            sprintf('Can\'t find transformer with ID "%s"', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($transformer);
    }
}
