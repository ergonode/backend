<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Model;

/**
 */
class ProductModel
{
    /**
     * @var array
     */
    private array $versions;

    /**
     */
    public function __construct()
    {
        $this->versions = [];
    }

    /**
     * @param string $code
     * @param array  $version
     */
    public function add(string $code, array $version): void
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
