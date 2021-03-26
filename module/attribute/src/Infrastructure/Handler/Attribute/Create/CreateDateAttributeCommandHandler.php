<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateDateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Application\Event\AttributeCreatedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class CreateDateAttributeCommandHandler
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
    public function __invoke(CreateDateAttributeCommand $command): void
    {
        $attribute = new DateAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $command->getScope(),
            $command->getFormat(),
        );

        foreach ($command->getGroups() as $group) {
            $attribute->addGroup($group);
        }

        $this->attributeRepository->save($attribute);
        $this->eventBus->dispatch(new AttributeCreatedEvent($attribute));
    }
}
