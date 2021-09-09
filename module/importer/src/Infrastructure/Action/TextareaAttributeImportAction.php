<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Import\Attribute\AbstractImportAttributeCommand;
use Ergonode\Importer\Domain\Command\Import\Attribute\ImportTextareaAttributeCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class TextareaAttributeImportAction extends AbstractAttributeImportAction
{
    /**
     * @throws ImportException
     */
    public function action(ImportTextareaAttributeCommand $command): void
    {
        $this->validate($command);
        /** @var TextareaAttribute $attribute */
        $attribute = $this->findAttribute(new AttributeCode($command->getCode()));
        $richEdit = $command->getParameter(TextareaAttribute::RICH_EDIT) === 'true' ? true : false;

        if (!$attribute) {
            $attribute = new TextareaAttribute(
                AttributeId::fromKey($command->getCode()),
                new AttributeCode($command->getCode()),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeScope($command->getScope()),
                $richEdit
            );
        } else {
            $this->updateAttribute($command, $attribute);
            $attribute->changeRichEdit($richEdit);
        }
        $this->processSuccessfulImport($attribute, $command);
    }

    protected function validate(AbstractImportAttributeCommand $command): void
    {
        parent::validate($command);

        if (null === $command->getParameter(TextareaAttribute::RICH_EDIT)) {
            throw new ImportException(
                'Rich text editor parameter for attribute {code} is empty',
                ['{code}' => $command->getCode()]
            );
        }
    }
}
