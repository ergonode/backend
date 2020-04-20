<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateTextAttributeCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;

/**
 */
class UpdateTextAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
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
     * @param UpdateTextAttributeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateTextAttributeCommand $command): void
    {
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::notNull($attribute);
        $attribute = $this->update($command, $attribute);

        $this->attributeRepository->save($attribute);
    }
}
