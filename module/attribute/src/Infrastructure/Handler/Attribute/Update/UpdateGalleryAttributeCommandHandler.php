<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateGalleryAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Webmozart\Assert\Assert;

/**
 */
class UpdateGalleryAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
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
     * @param UpdateGalleryAttributeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateGalleryAttributeCommand $command): void
    {
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::isInstanceOf($attribute, GalleryAttribute::class);
        $attribute = $this->update($command, $attribute);

        $this->attributeRepository->save($attribute);
    }
}
