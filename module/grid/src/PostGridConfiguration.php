<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\Grid\Request\FilterValueCollection;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Request\FilterValue;
use Ergonode\Grid\Request\RequestColumn;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

class PostGridConfiguration implements GridConfigurationInterface
{
    public const OFFSET = 0;
    public const LIMIT = 1000;

    private int $offset;

    private int $limit;

    private ?string $field;

    private string $order;

    private FilterValueCollection $filters;

    /**
     * @var array
     */
    private array $columns;

    private string $view;

    private bool $extended;

    public function __construct(Request $request)
    {
        $this->columns = [];

        $this->limit = (int) $request->request->get('limit', self::LIMIT);
        $this->offset = (int) $request->request->get('offset', self::OFFSET);
        $this->field =  $request->request->get('field');
        $this->order = strtoupper($request->request->get('order', self::DESC));

        $this->filters = new FilterValueCollection();
        foreach ($request->request->get('filters', []) as $filter) {
            if (!array_key_exists('column', $filter) || !array_key_exists('operator', $filter)) {
                throw new \InvalidArgumentException();
            }

            $column = $filter['column'];
            $operator = $filter['operator'];
            $value = $filter['value'] ?? null;
            $language = array_key_exists('language', $filter) ? new Language($filter['language']) : null;
            $key = $filter['column'];
            if ($language) {
                $key = sprintf('%s:%s', $key, $language->getCode());
            }

            $this->filters->addFilter($key, new FilterValue($column, $operator, $value, $language));
            $this->columns[$key] = new RequestColumn($column, $language, false);
        }

        foreach ($request->request->get('columns', []) as $column) {
            $name = $column['name'];
            $key = $column['name'];
            $language = array_key_exists('language', $column) ? new Language($column['language']) : null;
            if ($language) {
                $key = sprintf('%s:%s', $key, $language->getCode());
            }
            $this->columns[$key] = new RequestColumn($name, $language);
        }

        $this->view = $request->request->get('view', GridConfigurationInterface::VIEW_GRID);
        $this->extended = $request->request->get('extended', false);

        Assert::oneOf($this->order, self::ORDER);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

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

    public function getView(): string
    {
        return $this->view;
    }

    public function isExtended(): bool
    {
        return $this->extended;
    }
}
