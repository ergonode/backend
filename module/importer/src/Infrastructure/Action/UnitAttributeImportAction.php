<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\Import\Attribute\AbstractImportAttributeCommand;
use Ergonode\Importer\Domain\Command\Import\Attribute\ImportUnitAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class UnitAttributeImportAction extends AbstractAttributeImportAction
{
    private UnitRepositoryInterface $unitRepository;

    public function __construct(
        UnitRepositoryInterface $unitRepository,
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        ImportRepositoryInterface $importerRepository
    ) {
        $this->unitRepository = $unitRepository;
        parent::__construct($attributeQuery, $attributeRepository, $importerRepository);
    }

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

    protected function validate(AbstractImportAttributeCommand $command): void
    {
        parent::validate($command);

        if (null === $command->getParameter(UnitAttribute::UNIT)) {
            throw new ImportException(
                'Unit parameter for attribute {code} is empty',
                ['{code}' => $command->getCode()]
            );
        }

        if (null === ($this->unitRepository->load(new UnitId($command->getParameter(UnitAttribute::UNIT))))) {
            throw new ImportException(
                'Unit parameter {unit} for attribute {code} does not exist in the system',
                ['{unit}' => $command->getParameter(UnitAttribute::UNIT), '{code}' => $command->getCode()]
            );
        }
    }
}
