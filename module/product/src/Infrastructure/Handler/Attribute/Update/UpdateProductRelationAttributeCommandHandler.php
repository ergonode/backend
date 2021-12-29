<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Attribute\Update\UpdateProductRelationAttributeCommand;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class UpdateProductRelationAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateProductRelationAttributeCommand $command): void
    {
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::isInstanceOf($attribute, ProductRelationAttribute::class);
        $attribute = $this->update($command, $attribute);

        $this->attributeRepository->save($attribute);
    }
}
