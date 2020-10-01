<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportAttribute;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalAttributeCreatedEventProjector
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AttributeCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(AttributeCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $attribute = new ExportAttribute(
            $id,
            $event->getCode()->getValue(),
            $event->getLabel(),
            $event->getType(),
            true,
            $event->getParameters()
        );

        $this->repository->save($attribute);
    }
}
