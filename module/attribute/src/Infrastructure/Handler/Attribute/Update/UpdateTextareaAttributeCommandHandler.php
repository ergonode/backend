<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateTextareaAttributeCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;

/**
 */
class UpdateTextareaAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
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
     * @param UpdateTextareaAttributeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateTextareaAttributeCommand $command): void
    {
        /** @var TextareaAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::isInstanceOf($attribute, TextareaAttribute::class);
        $attribute = $this->update($command, $attribute);
        $attribute->changeRichEdit($command->isRichEdit());

        $this->attributeRepository->save($attribute);
    }
}
