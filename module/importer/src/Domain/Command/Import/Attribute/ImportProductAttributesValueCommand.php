<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportProductAttributesValueCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    /**
     * @var TranslatableString[]
     */
    private array $attributes;

    private string $sku;

    /**
     * @param TranslatableString[] $attributes
     */
    public function __construct(ImportLineId $id, ImportId $importId, array $attributes, string $sku)
    {
        $this->id = $id;
        $this->importId = $importId;
        $this->attributes = $attributes;
        $this->sku = $sku;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return TranslatableString[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getSku(): string
    {
        return $this->sku;
    }
}
