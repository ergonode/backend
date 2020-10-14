<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class ProductModel
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $sku;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $template;

    /**
     * @var array
     */
    private array $attributes = [];

    /**
     * @param string $id
     * @param string $sku
     * @param string $type
     * @param string $template
     */
    public function __construct(
        string $id,
        string $sku,
        string $type,
        string $template
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->type = $type;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $code
     * @param string $language
     * @param string $value
     */
    public function addAttribute(string $code, string $language, string $value): void
    {
        if (!array_key_exists($code, $this->attributes)) {
            $this->attributes[$code] = [];
        }

        $this->attributes[$code][$language] = $value;
    }
}
