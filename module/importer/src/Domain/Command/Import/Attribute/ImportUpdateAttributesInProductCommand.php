<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class ImportUpdateAttributesInProductCommand implements ImporterCommandInterface
{
    private ImportId $importId;

    /**
     * @var TranslatableString[]
     */
    private array $attributes;

    private Sku $sku;

    /**
     * @param TranslatableString[] $attributes
     */
    public function __construct(ImportId $importId, array $attributes, Sku $sku)
    {
        $this->importId = $importId;
        $this->attributes = $attributes;
        $this->sku = $sku;
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

    public function getSku(): Sku
    {
        return $this->sku;
    }
}
