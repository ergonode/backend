<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;
use Ergonode\Exporter\Domain\Exception\AttributeNotFoundException;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;

/**
 */
class DbalAttributeOptionRemovedEventProjector
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
     * @param OptionRemovedEvent $event
     *
     * @throws AttributeNotFoundException
     */
    public function __invoke(OptionRemovedEvent $event): void
    {
//        $id = Uuid::fromString($event->getAggregateId()->getValue());
//        $attribute = $this->repository->load($id);
//        if (null === $attribute) {
//            throw new AttributeNotFoundException($event->getAggregateId()->getValue());
//        }
//
//        $attribute->removeOption($event->getKey()->getValue());
//        $this->repository->save($attribute);
    }
}
