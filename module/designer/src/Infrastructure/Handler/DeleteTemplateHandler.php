<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Designer\Application\Event\TemplateDeletedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class DeleteTemplateHandler
{
    private TemplateRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    private ApplicationEventBusInterface $eventBus;

    public function __construct(
        TemplateRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver,
        ApplicationEventBusInterface $eventBus
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws ExistingRelationshipsException
     */
    public function __invoke(DeleteTemplateCommand $command): void
    {
        $template = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $template,
            Template::class,
            sprintf('Can\'t find template with ID "%s"', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($template);
        $this->eventBus->dispatch(new TemplateDeletedEvent($template));
    }
}
