<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Create;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateFileAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\FileAttribute;

class CreateFileAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateFileAttributeCommand $command): void
    {
        $attribute = new FileAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $command->getScope()
        );

        foreach ($command->getGroups() as $group) {
            $attribute->addGroup($group);
        }

        $this->attributeRepository->save($attribute);
    }
}
