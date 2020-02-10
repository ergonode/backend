<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Repository;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;

/**
 */
class DbalTemplateRepository implements TemplateRepositoryInterface
{
    /**
     * @var DomainEventStoreInterface
     */
    private $eventStore;

    /**
     * @var EventBusInterface
     */
    private $eventBus;

    /**
     * @param DomainEventStoreInterface $eventStore
     * @param EventBusInterface         $eventBus
     */
    public function __construct(DomainEventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        $this->eventStore = $eventStore;
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
        $eventStream = $this->eventStore->load($id);
        if ($eventStream->count() > 0) {
            $class = new \ReflectionClass(Template::class);
            /** @var Template $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof Template) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Template::class));
            }

            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Template $template): void
    {
        $events = $template->popEvents();

        $this->eventStore->append($template->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
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
        $this->save($template);

        $this->eventStore->delete($template->getId());
    }
}
