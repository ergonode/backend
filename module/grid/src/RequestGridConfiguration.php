<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Model\RequestColumn;
use Ergonode\Grid\Request\FilterCollection;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

/**
 */
class RequestGridConfiguration implements GridConfigurationInterface
{
    public const OFFSET = 0;
    public const LIMIT = 1000;
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const FILTER = null;
    public const COLUMNS = null;
    public const SHOW = 'COLUMN, DATA, CONFIGURATION, INFO';

    private const ORDER = [
        self::ASC,
        self::DESC,
    ];

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $order;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $show;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->columns = [];

        $this->limit = (int) $request->query->get('limit', self::LIMIT);
        $this->offset = (int) $request->query->get('offset', self::OFFSET);
        $this->field = $request->query->has('field') ? (string) $request->query->get('field') : null;
        $this->order = strtoupper($request->query->get('order', self::DESC));
        if ($request->query->has('columns')) {
            $columns = array_map('trim', explode(',', $request->query->get('columns')));
            foreach ($columns as $column) {
                $data = explode(':', $column);
                $key = $data[0];
                if (empty($key)) {
                    continue;
                }

                $language = null;
                if (isset($data[1])) {
                    $language = new Language($data[1]);
                }

                $this->columns[$column] = new RequestColumn($key, $language);
            }
        }

        $filters = $request->query->get('filter', self::FILTER);
        $this->filters = new FilterCollection($filters);

        $this->show = array_map('trim', explode(',', $request->query->get('show', self::SHOW)));
        Assert::oneOf($this->order, self::ORDER);
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @return FilterCollection
     */
    public function getFilters(): FilterCollection
    {
        return $this->filters;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getShow(): array
    {
        return $this->show;
    }
}
