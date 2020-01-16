<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Grid;

use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Grid\Request\RequestColumn;

/**
 */
interface GridConfigurationInterface
{
    public const PARAMETER_ALLOW_COLUMN_RESIZE = 'allow_column_resize';
    public const PARAMETER_ALLOW_COLUMN_EDIT = 'allow_column_edit';
    public const PARAMETER_ALLOW_COLUMN_MOVE = 'allow_column_move';

    public const DEFAULT_PARAMETERS = [
        self::PARAMETER_ALLOW_COLUMN_RESIZE => false,
        self::PARAMETER_ALLOW_COLUMN_EDIT => false,
        self::PARAMETER_ALLOW_COLUMN_MOVE => false,
    ];

    public const VIEW_GRID = 'grid';
    public const VIEW_LIST = 'list';

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return string|null
     */
    public function getField(): ?string;

    /**
     * @return string
     */
    public function getOrder(): string;

    /**
     * @return RequestColumn[]
     */
    public function getColumns(): array;

    /**
     * @return FilterValueCollection
     */
    public function getFilters(): FilterValueCollection;

    /**
     * @return string
     */
    public function getView(): string;

    /**
     * @return bool
     */
    public function isExtended(): bool;
}
