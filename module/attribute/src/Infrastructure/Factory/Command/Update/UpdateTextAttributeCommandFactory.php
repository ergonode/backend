<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command\Update;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateTextAttributeCommand;
use Ergonode\Attribute\Application\Model\Attribute\UnitAttributeFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Infrastructure\Factory\Command\UpdateAttributeCommandFactoryInterface;

/**
 */
class UpdateTextAttributeCommandFactory implements UpdateAttributeCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return $type === TextAttribute::TYPE;
    }

    /**
     * @param AttributeId   $id
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     */
    public function create(AttributeId $id, FormInterface $form): DomainCommandInterface
    {
        /** @var UnitAttributeFormModel $data */
        $data = $form->getData();

        return new UpdateTextAttributeCommand(
            $id,
            new TranslatableString($data->label),
            new TranslatableString($data->hint),
            new TranslatableString($data->placeholder),
            $data->multilingual,
            $data->groups,
        );
    }
}