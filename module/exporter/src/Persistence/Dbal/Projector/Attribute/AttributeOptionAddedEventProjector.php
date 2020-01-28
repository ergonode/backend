<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\AttributeOptionAddedEvent;
use Ergonode\Exporter\Domain\Exception\AttributeNotFoundException;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeOptionAddedEventProjector
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * AttributeOptionAddedEventProjector constructor.
     * @param AttributeRepositoryInterface $repository
     * @param SerializerInterface          $serializer
     */
    public function __construct(AttributeRepositoryInterface $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param AttributeOptionAddedEvent $event
     *
     * @throws AttributeNotFoundException
     */
    public function __invoke(AttributeOptionAddedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $attribute = $this->repository->load($id);
        if (null === $attribute) {
            throw new AttributeNotFoundException($event->getAggregateId()->getValue());
        }

        $value = $event->getOption()->getValue();
        if ($event->getOption()->isMultilingual()) {
            $value = $this->serializer->serialize($event->getOption()->getValue(), 'json');
        }

        $attribute->changeOrCreateOption(
            $event->getKey()->getValue(),
            $value
        );

        $this->repository->save($attribute);
    }
}
