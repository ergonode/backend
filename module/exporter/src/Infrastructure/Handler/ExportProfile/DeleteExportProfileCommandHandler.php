<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\ExportProfile;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Exporter\Domain\Command\ExportProfile\DeleteExportProfileCommand;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteExportProfileCommandHandler
{
    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private RelationshipsResolverInterface $relationshipsResolver;

    /**
     * @param ExportProfileRepositoryInterface $repository
     * @param RelationshipsResolverInterface   $relationshipsResolver
     */
    public function __construct(
        ExportProfileRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param DeleteExportProfileCommand $command
     *
     * @throws ExistingRelationshipsException
     * @throws \ReflectionException
     */
    public function __invoke(DeleteExportProfileCommand $command): void
    {
        $exportProfile = $this->repository->load($command->getId());
        Assert::notNull($exportProfile);

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($exportProfile);
    }
}
