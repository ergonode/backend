<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Exporter\Domain\Exception\AttributeNotFoundException;
use Ergonode\Exporter\Domain\Repository\AttributeRepositoryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeParameterChangeEventProjector
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
     * @param AttributeParameterChangeEvent $event
     *
     * @throws AttributeNotFoundException
     */
    public function __invoke(AttributeParameterChangeEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $attribute = $this->repository->load($id);
        if (null === $attribute) {
            throw new AttributeNotFoundException($event->getAggregateId()->getValue());
        }

        $attribute->changeParameter(
            $event->getName(),
            $event->getTo()
        );
        $this->repository->save($attribute);
    }
}
