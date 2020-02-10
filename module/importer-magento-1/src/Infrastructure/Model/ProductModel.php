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
        if (!array_key_exists($code, $this->versions)) {
            $this->versions[$code] = $version;
        } else {
            foreach ($version as $field => $value) {
                if($value !== '') {
                    $this->versions[$code][$field] .= ',' .$value;
                }
            }
        }
    }

    /**
     * @param string $code
     *
     * @param bool   $existed
     *
     * @return array
     */
    public function get(string $code, bool $existed = false): array
    {
        if(!$existed) {
            $result = $this->versions[$code];
        } else {
            $result = [];
            foreach ($this->versions[$code] as $field => $value) {
                if($value !== '') {
                    $result[$field] = $value;
                }
            }
        }

        return $result;
    }
}
