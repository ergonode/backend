<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command\Update;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateGalleryAttributeCommand;
use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Infrastructure\Factory\Command\UpdateAttributeCommandFactoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class UpdateGalleryAttributeCommandFactory implements UpdateAttributeCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === GalleryAttribute::TYPE;
    }

    /**
     * @param FormInterface $form \
     */
    public function create(AttributeId $id, FormInterface $form): AttributeCommandInterface
    {
        /** @var AttributeFormModel $data */
        $data = $form->getData();

        $groups = [];
        foreach ($data->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        return new UpdateGalleryAttributeCommand(
            $id,
            new TranslatableString($data->label),
            new TranslatableString($data->hint),
            new TranslatableString($data->placeholder),
            new AttributeScope($data->scope),
            $groups,
        );
    }
}
