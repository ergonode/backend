<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateMultiSelectAttributeCommand;
use Ergonode\Attribute\Application\Event\AttributeUpdatedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateMultiSelectAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
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
    public function __invoke(UpdateMultiSelectAttributeCommand $command): void
    {
        /** @var MultiSelectAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::isInstanceOf($attribute, MultiSelectAttribute::class);
        $attribute = $this->update($command, $attribute);

        $this->attributeRepository->save($attribute);
        $this->eventBus->dispatch(new AttributeUpdatedEvent($attribute));
    }
}
