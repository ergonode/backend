<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\DateAttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateDateAttributeCommand;
use Ergonode\Attribute\Domain\Command\Attribute\CreateAttributeCommandInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class CreateDateAttributeCommandFactory implements CreateAttributeCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === DateAttribute::TYPE;
    }
    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateAttributeCommandInterface
    {
        /** @var DateAttributeFormModel $data */
        $data = $form->getData();

        $groups = [];
        foreach ($data->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        return new CreateDateAttributeCommand(
            new AttributeCode($data->code),
            new TranslatableString($data->label),
            new TranslatableString($data->hint),
            new TranslatableString($data->placeholder),
            new AttributeScope($data->scope),
            new DateFormat(DateFormat::YYYY_MM_DD),
            $groups,
        );
    }
}
