<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Grid\Request\RequestColumn;

interface GridConfigurationInterface
{
    public const VIEW_GRID = 'grid';
    public const VIEW_LIST = 'list';

    public function getOffset(): int;

    public function getLimit(): int;

    public function getField(): ?string;

    public function getOrder(): string;

    /**
     * @return RequestColumn[]
     */
    public function getColumns(): array;

    public function getFilters(): FilterValueCollection;

    public function getView(): string;

    public function isExtended(): bool;
}
