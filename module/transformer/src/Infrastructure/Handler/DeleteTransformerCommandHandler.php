<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Transformer\Domain\Command\DeleteTransformerCommand;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteTransformerCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @param TransformerRepositoryInterface $repository
     * @param RelationshipsResolverInterface $relationshipsResolver
     */
    public function __construct(
        TransformerRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param DeleteTransformerCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteTransformerCommand $command)
    {
        $transformer = $this->repository->load($command->getId());
        Assert::isInstanceOf($transformer, Transformer::class, sprintf('Can\'t find transformer with ID "%s"', $command->getId()));

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($transformer);
    }
}
