<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

class Shopware6QueryBuilder
{
    public const EQUALS = 'equals';

    /**
     * @var array
     */
    private array $parts = [];

    /**
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * @var int|null
     */
    private ?int $page = null;

    /**
     * @param int $limit
     *
     * @return Shopware6QueryBuilder
     */
    public function limit(int $limit): Shopware6QueryBuilder
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return Shopware6QueryBuilder
     */
    public function setPage(int $page): Shopware6QueryBuilder
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param string $type
     * @param string $field
     * @param string $value
     *
     * @return $this
     */
    public function add(string $type, string $field, string $value): Shopware6QueryBuilder
    {
        $this->parts[] = [
            'query' => [
                'type' => $type,
                'field' => $field,
                'value' => $value,
            ],
        ];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return Shopware6QueryBuilder
     */
    public function equals(string $field, string $value): Shopware6QueryBuilder
    {
        return $this->add(self::EQUALS, $field, $value);
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return $this
     */
    public function sort(string $field, string $value): Shopware6QueryBuilder
    {
        $this->parts[0]['sort'] =
            [
                'field' => $field,
                'order' => $value,
            ];

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $param['query'] = [];
        if (count($this->parts) > 0) {
            $param['query'] = $this->parts;
        }

        if ($this->isLimit()) {
            $param['limit'] = $this->limit;

            if ($this->page > 0) {
                $param['page'] = $this->page;
            }
        }

        return http_build_query($param);
    }

    /**
     * @return bool
     */
    private function isLimit(): bool
    {
        return null !== $this->limit;
    }
}
