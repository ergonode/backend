<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Grid;

use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Grid\Request\RequestColumn;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\Grid\Request\FilterValue;

class BatchActionFilterGridConfiguration implements GridConfigurationInterface
{
    public const OFFSET = 0;
    public const LIMIT = 2147483647;

    private FilterValueCollection $filters;

    /**
     * @var RequestColumn[]
     */
    private array $columns;

    public function __construct(BatchActionFilterInterface $filter)
    {
        $this->columns = [];
        $this->filters = new FilterValueCollection($filter->getQuery());
        $ids = $filter->getIds();

        if ($ids) {
            $operator = $ids->isIncluded() ? '=' : '!=';
            $filer = new FilterValue('id', $operator, implode(',', $ids->getList()));
            $this->filters->addFilter('id', $filer);
        }

        /** @var FilterValue[] $elements */
        foreach ($this->filters as $key => $elements) {
            foreach ($elements as $element) {
                $this->columns[$key] = new RequestColumn($element->getColumn(), $element->getLanguage(), false);
            }
        }
    }

    public function getOffset(): int
    {
        return self::OFFSET;
    }

    public function getLimit(): int
    {
        return self::LIMIT;
    }

    public function getField(): ?string
    {
        return null;
    }

    public function getOrder(): string
    {
        return self::ASC;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getFilters(): FilterValueCollection
    {
        return $this->filters;
    }

    public function getView(): string
    {
        return self::VIEW_LIST;
    }

    public function isExtended(): bool
    {
        return false;
    }
}
