<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Import\Attribute\AbstractImportAttributeCommand;
use Ergonode\Importer\Domain\Command\Import\Attribute\ImportDateAttributeCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DateAttributeImportAction extends AbstractAttributeImportAction
{
    /**
     * @throws ImportException
     */
    public function action(ImportDateAttributeCommand $command): void
    {
        $this->validate($command);
        $format = new DateFormat($command->getParameter(DateAttribute::FORMAT));
        /** @var DateAttribute $attribute */
        $attribute = $this->findAttribute(new AttributeCode($command->getCode()));
        if (!$attribute) {
            $attribute = new DateAttribute(
                AttributeId::fromKey($command->getCode()),
                new AttributeCode($command->getCode()),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeScope($command->getScope()),
                $format
            );
        } else {
            $this->updateAttribute($command, $attribute);
            $attribute->changeFormat($format);
        }
        $this->processSuccessfulImport($attribute, $command);
    }

    protected function validate(AbstractImportAttributeCommand $command): void
    {
        parent::validate($command);

        if (null === $command->getParameter(DateAttribute::FORMAT)) {
            throw new ImportException(
                'Date format parameter for attribute {code} does not exist',
                ['{code}' => $command->getCode()]
            );
        }

        if (!DateFormat::isValid($command->getParameter(DateAttribute::FORMAT))) {
            throw new ImportException(
                'Date format {format} for attribute {code} is not valid',
                ['{format}' => $command->getParameter(DateAttribute::FORMAT), '{code}' => $command->getCode()]
            );
        }
    }
}
