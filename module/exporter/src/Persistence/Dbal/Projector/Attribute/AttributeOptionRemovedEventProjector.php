<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Attribute;

use Ergonode\Exporter\Domain\Exception\AttributeNotFoundException;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;

/**
 */
class AttributeOptionRemovedEventProjector
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * AttributeOptionRemovedEventProjector constructor.
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
