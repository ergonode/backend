<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Reader\Domain\Command\DeleteReaderCommand;
use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteReaderCommandHandler
{
    /**
     * @var ReaderRepositoryInterface
     */
    private $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @param ReaderRepositoryInterface      $repository
     * @param RelationshipsResolverInterface $relationshipsResolver
     */
    public function __construct(
        ReaderRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param DeleteReaderCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteReaderCommand $command)
    {
        $role = $this->repository->load($command->getId());
        Assert::isInstanceOf($role, Reader::class, sprintf('Can\'t find reader with ID "%s"', $command->getId()));

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($role);
    }
}
