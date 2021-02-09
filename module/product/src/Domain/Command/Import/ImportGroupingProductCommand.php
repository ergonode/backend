<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportGroupingProductCommand implements ImporterCommandInterface
{
    private ImportId $importId;

    private string $sku;

    private string $template;

    /**
     * @var string[]
     */
    private array $categories;

    /**
     * @var string[]
     */
    private array $children;

    /**
     * @var string[]
     */
    private array $attributes;

    /**
     * @param string[]             $categories
     * @param string[]             $children
     * @param TranslatableString[] $attributes
     */
    public function __construct(
        ImportId $importId,
        string $sku,
        string $template,
        array $categories,
        array $children,
        array $attributes
    ) {
        $this->importId = $importId;
        $this->sku = $sku;
        $this->template = $template;
        $this->categories = $categories;
        $this->children = $children;
        $this->attributes = $attributes;
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
     * @return Sku[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
