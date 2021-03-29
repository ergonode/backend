<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Ergonode\Designer\Application\Event\TemplateCreatedEvent;
use Ergonode\Designer\Application\Event\TemplateUpdatedEvent;
use Ergonode\Designer\Application\Event\TemplateDeletedEvent;

class EventStoreTemplateRepository implements TemplateRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    protected ApplicationEventBusInterface $eventBus;

    public function __construct(EventStoreManagerInterface $manager, ApplicationEventBusInterface $eventBus)
    {
        $this->manager = $manager;
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritDoc}
     *
     * @return Template|null
     *
     * @throws \ReflectionException
     */
    public function load(TemplateId $id): ?Template
    {
        /** @var Template $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, Template::class);

        return $result;
    }

    /**
     * @throws DBALException
     */
    public function save(Template $template): void
    {
        $isNew = $template->isNew();
        $this->manager->save($template);
        if ($isNew) {
            $this->eventBus->dispatch(new TemplateCreatedEvent($template));
        } else {
            $this->eventBus->dispatch(new TemplateUpdatedEvent($template));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Template $template): void
    {
        $template->apply(new TemplateRemovedEvent($template->getId()));
        $this->manager->save($template);

        $this->manager->delete($template);
        $this->eventBus->dispatch(new TemplateDeletedEvent($template));
    }
}
