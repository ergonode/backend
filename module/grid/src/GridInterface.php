<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

interface GridInterface
{
    public function addColumn(string $id, ColumnInterface $column): self;

    /**
     * @return ColumnInterface[]
     */
    public function getColumns(): array;

    public function orderBy(string $field, string $order): void;

    public function getField(): ?string;

    public function getOrder(): string;
}
