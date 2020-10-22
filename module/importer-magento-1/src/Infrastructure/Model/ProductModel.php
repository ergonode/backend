<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Model;

use Ergonode\Product\Domain\ValueObject\Sku;

class ProductModel
{
    /**
     * @var Sku
     */
    private Sku $sku;

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
    private array $versions;

    /**
     * @param Sku    $sku
     * @param string $type
     * @param string $template
     */
    public function __construct(Sku $sku, string $type, string $template)
    {
        $this->sku = $sku;
        $this->type = $type;
        $this->versions = [];
        $this->template = $template;
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
     * @param string $code
     * @param array  $version
     */
    public function set(string $code, array $version): void
    {
        $this->versions[$code] = $version;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function has(string $code): bool
    {
        return array_key_exists($code, $this->versions);
    }

    /**
     * @param string $code
     *
     * @return array
     */
    public function get(string $code): array
    {
        return $this->versions[$code];
    }
}
