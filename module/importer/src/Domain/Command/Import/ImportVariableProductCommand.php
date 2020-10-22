<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class ImportVariableProductCommand implements DomainCommandInterface
{
    private ImportId $importId;

    private Sku $sku;

    private string $template;

    /**
     * @var CategoryCode[]
     */
    private array $categories;

    /**
     * @var AttributeCode[]
     */
    private array $bindings;

    /**
     * @var Sku[]
     */
    private array $children;

    /**
     * @var string[]
     */
    private array $attributes;

    /**
     * @param CategoryCode[]  $categories
     * @param AttributeCode[] $bindings
     * @param Sku[]           $children
     * @param string[]        $attributes
     */
    public function __construct(
        ImportId $importId,
        Sku $sku,
        string $template,
        array $categories,
        array $bindings,
        array $children,
        array $attributes
    ) {
        Assert::allIsInstanceOf($categories, CategoryCode::class);
        Assert::allIsInstanceOf($bindings, AttributeCode::class);
        Assert::allIsInstanceOf($children, Sku::class);

        $this->importId = $importId;
        $this->sku = $sku;
        $this->template = $template;
        $this->categories = $categories;
        $this->bindings = $bindings;
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
     * @return AttributeCode[]
     */
    public function getBindings(): array
    {
        return $this->bindings;
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
