<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\PriceAttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreatePriceAttributeCommand;
use Ergonode\Attribute\Domain\Command\Attribute\CreateAttributeCommandInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Money\Currency;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class CreatePriceAttributeCommandFactory implements CreateAttributeCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === PriceAttribute::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateAttributeCommandInterface
    {
        /** @var PriceAttributeFormModel $data */
        $data = $form->getData();

        $groups = [];
        foreach ($data->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        return new CreatePriceAttributeCommand(
            new AttributeCode($data->code),
            new TranslatableString($data->label),
            new TranslatableString($data->hint),
            new TranslatableString($data->placeholder),
            new AttributeScope($data->scope),
            new Currency($data->parameters->currency),
            $groups,
        );
    }
}
