<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateTextareaAttributeCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;

class UpdateTextareaAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateTextareaAttributeCommand $command): void
    {
        /** @var TextareaAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getId());

        if (!$attribute instanceof TextareaAttribute) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    TextareaAttribute::class,
                    get_debug_type($attribute)
                )
            );
        }
        $this->update($command, $attribute);
        $attribute->changeRichEdit($command->isRichEdit());

        $this->attributeRepository->save($attribute);
    }
}
