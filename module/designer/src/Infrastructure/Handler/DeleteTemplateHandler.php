<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteTemplateHandler
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @param TemplateRepositoryInterface    $repository
     * @param RelationshipsResolverInterface $relationshipsResolver
     */
    public function __construct(
        TemplateRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @param DeleteTemplateCommand $command
     *
     * @throws ExistingRelationshipsException
     */
    public function __invoke(DeleteTemplateCommand $command)
    {
        $template = $this->repository->load($command->getId());
        Assert::isInstanceOf($template, Template::class, sprintf('Can\'t find template with ID "%s"', $command->getId()));

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($template);
    }
}
