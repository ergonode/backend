<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Grid;

use Ergonode\Grid\Model\RequestColumn;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
interface GridConfigurationInterface
{
    public const CONFIGURATION_SHOW_DATA = 'DATA';
    public const CONFIGURATION_SHOW_COLUMN = 'COLUMN';
    public const CONFIGURATION_SHOW_INFO = 'INFO';
    public const CONFIGURATION_SHOW_CONFIGURATION = 'CONFIGURATION';

    public const PARAMETER_ALLOW_COLUMN_RESIZE = 'allow_column_resize';
    public const PARAMETER_ALLOW_COLUMN_EDIT = 'allow_column_edit';
    public const PARAMETER_ALLOW_COLUMN_MOVE = 'allow_column_move';

    public const DEFAULT_PARAMETERS = [
        self::PARAMETER_ALLOW_COLUMN_RESIZE => false,
        self::PARAMETER_ALLOW_COLUMN_EDIT => false,
        self::PARAMETER_ALLOW_COLUMN_MOVE => false,
    ];

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
     * @return FilterCollection
     */
    public function getFilters(): FilterCollection;

    /**
     * @return array
     */
    public function getShow(): array;
}
