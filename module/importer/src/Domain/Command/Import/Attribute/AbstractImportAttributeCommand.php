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
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

abstract class AbstractImportAttributeCommand implements ImporterCommandInterface
{
    private ImportId $importId;
    private AttributeCode $code;
    private AttributeScope $scope;
    private TranslatableString $label;
    private TranslatableString $hint;
    private TranslatableString $placeholder;

    public function __construct(
        ImportId $importId,
        AttributeCode $code,
        AttributeScope $scope,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder
    ) {
        $this->importId = $importId;
        $this->code = $code;
        $this->scope = $scope;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getScope(): AttributeScope
    {
        return $this->scope;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    public function getPlaceholder(): TranslatableString
    {
        return $this->placeholder;
    }
}
