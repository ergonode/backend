<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Create;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateDateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;

/**
 */
class CreateDateAttributeCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param CreateDateAttributeCommand $command
     *
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
            $attribute->addGroup(new AttributeGroupId($group));
        }

        $this->attributeRepository->save($attribute);
    }
}
