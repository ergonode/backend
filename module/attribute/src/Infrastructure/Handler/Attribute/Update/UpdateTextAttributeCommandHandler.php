<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateTextAttributeCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;

class UpdateTextAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateTextAttributeCommand $command): void
    {
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::isInstanceOf($attribute, TextAttribute::class);
        $attribute = $this->update($command, $attribute);

        $this->attributeRepository->save($attribute);
    }
}
