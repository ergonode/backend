<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\Grid\Request\FilterValue;
use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Grid\Request\RequestColumn;

class FilterGridConfiguration implements GridConfigurationInterface
{
    public const OFFSET = 0;
    public const LIMIT = 2147483647;
    public const ASC = 'ASC';
    public const FILTER = null;
    public const COLUMNS = null;

    private FilterValueCollection $filters;

    /**
     * @var RequestColumn[]
     */
    private array $columns;

    public function __construct(string $filters)
    {
        $this->columns = [];
        $this->filters = new FilterValueCollection($filters);
        foreach ($this->filters as $key => $elements) {
            /** @var FilterValue $element */
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

    /**
     * {@inheritDoc}
     */
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
