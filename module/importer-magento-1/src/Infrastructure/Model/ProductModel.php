<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Model;

use Ergonode\Product\Domain\ValueObject\Sku;

class ProductModel
{
    private Sku $sku;

    private string $type;

    private string $template;

    /**
     * @var array
     */
    private array $versions;

    public function __construct(Sku $sku, string $type, string $template)
    {
        $this->sku = $sku;
        $this->type = $type;
        $this->versions = [];
        $this->template = $template;
    }

    public function getSku(): Sku
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

    /**
     * @param array $version
     */
    public function set(string $code, array $version): void
    {
        $this->versions[$code] = $version;
    }

    public function has(string $code): bool
    {
        return array_key_exists($code, $this->versions);
    }

    /**
     * @return array
     */
    public function get(string $code): array
    {
        return $this->versions[$code];
    }
}
