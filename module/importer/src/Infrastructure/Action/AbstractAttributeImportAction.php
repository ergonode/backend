<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Import\Attribute\AbstractImportAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

abstract class AbstractAttributeImportAction
{

    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $attributeRepository;

    private ImportRepositoryInterface $importerRepository;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        ImportRepositoryInterface $importerRepository
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeRepository = $attributeRepository;
        $this->importerRepository = $importerRepository;
    }

    /**
     * @throws ImportException
     */
    protected function validate(AbstractImportAttributeCommand $command): void
    {
        if (!AttributeCode::isValid($command->getCode())) {
            throw new ImportException('Attribute code {code} is not valid', ['{code}' => $command->getCode()]);
        }

        if (!AttributeScope::isValid($command->getScope())) {
            throw new ImportException('Attribute Scope {scope} is not valid', ['{scope}' => $command->getScope()]);
        }
    }

    protected function updateExistingAttribute(AbstractImportAttributeCommand $command): ?AbstractAttribute
    {
        $attributeId = $this->attributeQuery->findAttributeIdByCode(new AttributeCode($command->getCode()));

        if ($attributeId) {
            $attribute = $this->attributeRepository->load($attributeId);
            if ($attribute) {
                $attribute->changeLabel(new TranslatableString($command->getLabel()));
                $attribute->changeHint(new TranslatableString($command->getHint()));
                $attribute->changePlaceholder(new TranslatableString($command->getPlaceholder()));
                $attribute->changeScope(new AttributeScope($command->getScope()));

                return $attribute;
            }
        }

        return null;
    }

    protected function processSuccessfulImport(
        AbstractAttribute $attribute,
        AbstractImportAttributeCommand $command
    ): void {
        $this->attributeRepository->save($attribute);
        $this->importerRepository->markLineAsSuccess($command->getId(), $attribute->getId());
    }
}
