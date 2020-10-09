<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Webmozart\Assert\Assert;

/**
 */
class ImportSimpleProductCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var Sku
     */
    private Sku $sku;

    /**
     * @var string
     */
    private string $template;

    /**
     * @var CategoryCode[]
     */
    private array $categories;

    /**
     * @var string[]
     */
    private array $attributes;

    /**
     * @param ImportId $importId
     * @param Sku      $sku
     * @param string   $template
     * @param array    $categories
     * @param array    $attributes
     */
    public function __construct(
        ImportId $importId,
        Sku $sku,
        string $template,
        array $categories,
        array $attributes = []
    ) {
        Assert::allIsInstanceOf($categories, CategoryCode::class);

        $this->importId = $importId;
        $this->sku = $sku;
        $this->template = $template;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return Sku
     */
    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @return string
     */
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
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
