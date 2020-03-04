<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Request\FilterValue;
use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Grid\Request\RequestColumn;
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

    private const ORDER = [
        self::ASC,
        self::DESC,
    ];

    /**
     * @var int
     */
    private int $offset;

    /**
     * @var int
     */
    private int $limit;

    /**
     * @var string|null
     */
    private ?string $field;

    /**
     * @var string
     */
    private string $order;

    /**
     * @var FilterValueCollection
     */
    private FilterValueCollection $filters;

    /**
     * @var array
     */
    private array $columns;

    /**
     * @var string
     */
    private string $view;

    /**
     * @var bool
     */
    private bool $extended;

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

        $filters = $request->query->get('filter', self::FILTER);
        $this->filters = new FilterValueCollection($filters);
        foreach ($this->filters as $key => $elements) {
            /** @var FilterValue $element */
            foreach ($elements as $element) {
                $this->columns[$key] = new RequestColumn($element->getColumn(), $element->getLanguage(), false);
            }
        }

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

        $this->view = $request->query->get('view', GridConfigurationInterface::VIEW_GRID);
        $this->extended = $request->query->has('extended') ? true : false;
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
     * @return FilterValueCollection
     */
    public function getFilters(): FilterValueCollection
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
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * @return bool
     */
    public function isExtended(): bool
    {
        return $this->extended;
    }
}
