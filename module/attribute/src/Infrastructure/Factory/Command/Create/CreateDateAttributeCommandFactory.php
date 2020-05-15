<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command\Create;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateDateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Application\Model\Attribute\DateAttributeFormModel;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

/**
 */
class CreateDateAttributeCommandFactory implements CreateAttributeCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return $type === DateAttribute::TYPE;
    }
    /**
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    public function create(FormInterface $form): DomainCommandInterface
    {
        /** @var DateAttributeFormModel $data */
        $data = $form->getData();

        return new CreateDateAttributeCommand(
            new AttributeCode($data->code),
            new TranslatableString($data->label),
            new TranslatableString($data->hint),
            new TranslatableString($data->placeholder),
            new AttributeScope($data->scope),
            new DateFormat(DateFormat::YYYY_MM_DD),
            $data->groups,
        );
    }
}
