<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportSimpleProductCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    private string $sku;

    private string $template;

    /**
     * @var String[]
     */
    private array $categories;

    /**
     * @var string[]
     */
    private array $attributes;

    /**
     * @param string[]             $categories
     * @param TranslatableString[] $attributes
     */
    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $sku,
        string $template,
        array $categories = [],
        array $attributes = []
    ) {
        $this->id = $id;
        $this->importId = $importId;
        $this->sku = $sku;
        $this->template = $template;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return TranslatableString[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
