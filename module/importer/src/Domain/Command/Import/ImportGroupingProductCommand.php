<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Webmozart\Assert\Assert;

class ImportGroupingProductCommand implements ImporterCommandInterface
{
    private ImportId $importId;

    private Sku $sku;

    private string $template;

    /**
     * @var CategoryCode[]
     */
    private array $categories;

    /**
     * @var Sku[]
     */
    private array $children;

    /**
     * @var string[]
     */
    private array $attributes;

    /**
     * @param CategoryCode[] $categories
     * @param Sku[]          $children
     * @param string[]       $attributes
     */
    public function __construct(
        ImportId $importId,
        Sku $sku,
        string $template,
        array $categories,
        array $children,
        array $attributes
    ) {
        Assert::allIsInstanceOf($children, Sku::class);

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

    public function getSku(): Sku
    {
        return $this->sku;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return CategoryCode[]
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
