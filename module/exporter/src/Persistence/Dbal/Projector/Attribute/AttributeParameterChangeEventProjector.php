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

/**
 */
class AttributeParameterChangeEventProjector
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * AttributeParameterChangeEventProjector constructor.
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
        $attribute = $this->repository->load($event->getAggregateId()->getValue());
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
