<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateGalleryAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

/**
 */
class CreateGalleryAttributeCommandHandler
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
     * @param CreateGalleryAttributeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateGalleryAttributeCommand $command): void
    {
        $attribute = new GalleryAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $command->isMultilingual()
        );

        foreach ($command->getGroups() as $group) {
            $attribute->addGroup(new AttributeGroupId($group));
        }

        $this->attributeRepository->save($attribute);
    }
}
