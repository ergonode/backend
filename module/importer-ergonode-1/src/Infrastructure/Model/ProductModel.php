<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ProductModel extends AbstractModel
{
    private string $sku;
    private string $type;
    private string $template;
    private array $attributes = [];

    /**
     * @var string[]
     */
    private array $categories = [];

    public function __construct(
        string $sku,
        string $type,
        string $template
    ) {
        $this->sku = $sku;
        $this->type = $type;
        $this->template = $template;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function addAttribute(string $code, string $language, string $value): void
    {
        if (!array_key_exists($code, $this->attributes)) {
            $this->attributes[$code] = new TranslatableString([]);
        }

        $this->attributes[$code] = $this->attributes[$code]->add(new Language($language), $value);
    }

    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(string $code): void
    {
        $this->categories[$code] = $code;
    }
}
