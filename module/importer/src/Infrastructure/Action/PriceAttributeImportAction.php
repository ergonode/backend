<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Import\Attribute\AbstractImportAttributeCommand;
use Ergonode\Importer\Domain\Command\Import\Attribute\ImportPriceAttributeCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Money\Currency;

class PriceAttributeImportAction extends AbstractAttributeImportAction
{
    /**
     * @throws ImportException
     */
    public function action(ImportPriceAttributeCommand $command): void
    {
        $this->validate($command);
        $attribute = $this->updateExistingAttribute($command);
        if (!$attribute) {
            $attribute = new PriceAttribute(
                AttributeId::fromKey($command->getCode()),
                new AttributeCode($command->getCode()),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeScope($command->getScope()),
                new Currency($command->getParameter(PriceAttribute::CURRENCY)),
            );
        }
        $this->processSuccessfulImport($attribute, $command);
    }

    protected function validate(AbstractImportAttributeCommand $command): void
    {
        parent::validate($command);

        if (null === $command->getParameter(PriceAttribute::CURRENCY)) {
            throw new ImportException(
                'Currency parameter for attribute {code} is empty',
                ['{code}' => $command->getCode()]
            );
        }
    }
}
