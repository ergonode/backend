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
use Ergonode\Attribute\Domain\ValueObject\DateFormat;

class ImportDateAttributeCommand extends AbstractImportAttributeCommand
{
    private DateFormat $format;

    public function __construct(
        ImportId $importId,
        AttributeCode $code,
        AttributeScope $scope,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        DateFormat $format
    ) {
        parent::__construct($importId, $code, $scope, $label, $hint, $placeholder);

        $this->format = $format;
    }

    public function getFormat(): DateFormat
    {
        return $this->format;
    }
}
