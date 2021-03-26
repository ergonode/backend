<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateUnitAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Ergonode\Attribute\Application\Event\AttributeUpdatedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateUnitAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    private ApplicationEventBusInterface $eventBus;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        ApplicationEventBusInterface $eventBus
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateUnitAttributeCommand $command): void
    {
        /** @var UnitAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getId());

        if (!$attribute instanceof UnitAttribute) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    UnitAttribute::class,
                    get_debug_type($attribute)
                )
            );
        }
        $this->update($command, $attribute);
        $attribute->changeUnit($command->getUnitId());

        $this->attributeRepository->save($attribute);
        $this->eventBus->dispatch(new AttributeUpdatedEvent($attribute));
    }
}
