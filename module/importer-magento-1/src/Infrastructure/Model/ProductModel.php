<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Model;

class ProductModel
{
    private string $sku;

    private string $type;

    private string $template;

    /**
     * @var string[][]
     */
    private array $versions;

    /**
     * @var string[]
     */
    private array $defaultVersion;

    /**
     * @param string[] $defaultVersion
     */
    public function __construct(string $sku, string $type, string $template, array $defaultVersion)
    {
        $this->sku = $sku;
        $this->type = $type;
        $this->versions = [];
        $this->defaultVersion = $defaultVersion;
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

    /**
     * @param string[] $version
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
     * @return string[]
     */
    public function get(string $code): array
    {
        if (!$this->has($code)) {
            throw new \OutOfRangeException("Missing '$code' version");
        }

        return $this->versions[$code];
    }

    /**
     * @param string[] $defaultVersion
     */
    public function setDefault(array $defaultVersion): void
    {
        $this->defaultVersion = $defaultVersion;
    }

    /**
     * @return string[]
     */
    public function getDefault(): array
    {
        return $this->defaultVersion;
    }
}
