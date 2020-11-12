<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateSelectAttributeCommand;
use Ergonode\Attribute\Domain\Command\Attribute\CreateAttributeCommandInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class CreateSelectAttributeCommandFactory implements CreateAttributeCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === SelectAttribute::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateAttributeCommandInterface
    {
        /** @var AttributeFormModel $data */
        $data = $form->getData();

        $groups = [];
        foreach ($data->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        return new CreateSelectAttributeCommand(
            new AttributeCode($data->code),
            new TranslatableString($data->label),
            new TranslatableString($data->hint),
            new TranslatableString($data->placeholder),
            new AttributeScope($data->scope),
            $groups,
        );
    }
}
