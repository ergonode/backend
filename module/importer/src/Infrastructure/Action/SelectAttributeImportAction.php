<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Import\Attribute\ImportSelectAttributeCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class SelectAttributeImportAction extends AbstractAttributeImportAction
{
    /**
     * @throws ImportException
     */
    public function action(ImportSelectAttributeCommand $command): void
    {
        $this->validate($command);
        $attribute = $this->updateExistingAttribute($command);
        if (!$attribute) {
            $attribute = new SelectAttribute(
                AttributeId::fromKey($command->getCode()),
                new AttributeCode($command->getCode()),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeScope($command->getScope()),
                $command->getParameters()
            );
        }
        $this->processSuccessfulImport($attribute, $command);
    }
}
