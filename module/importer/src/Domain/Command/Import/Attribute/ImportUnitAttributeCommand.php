<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportUnitAttributeCommand extends AbstractImportAttributeCommand
{
    private string $unit;

    public function __construct(
        ImportId $importId,
        AttributeCode $code,
        AttributeScope $scope,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        string $unit
    ) {
        parent::__construct($importId, $code, $scope, $label, $hint, $placeholder);

        $this->unit = $unit;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }
}
