<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Attribute\ImportUnitAttributeCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class UnitAttributeImportAction extends AbstractAttributeImportAction
{
    /**
     * @throws ImportException
     */
    public function action(ImportUnitAttributeCommand $command): void
    {
        $this->validate($command);
        $attribute = $this->updateExistingAttribute($command);
        if (!$attribute) {
            $attribute = new UnitAttribute(
                AttributeId::fromKey($command->getCode()),
                new AttributeCode($command->getCode()),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeScope($command->getScope()),
                new UnitId($command->getParameter(UnitAttribute::UNIT))
            );
        }
        $this->processSuccessfulImport($attribute, $command);
    }
}
