<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

interface ActionInterface
{
    public function getMethod() :string;

    public function getRoute(): string;

    public function getPrivilege(): ?string;

    public function getParameters(): array;

    public function getConditions(): array;
}