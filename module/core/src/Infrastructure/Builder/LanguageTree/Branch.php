<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Builder\LanguageTree;

use Ergonode\SharedKernel\Domain\AggregateId;

class Branch
{
    private AggregateId $id;

    private string $code;

    private int $left;

    private int $right;

    private ?AggregateId $parentId;

    public function __construct(
        AggregateId $id,
        string $code,
        int $left,
        int $right,
        ?AggregateId $parentId = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->left = $left;
        $this->right = $right;
        $this->parentId = $parentId;
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLeft(): int
    {
        return $this->left;
    }

    public function getRight(): int
    {
        return $this->right;
    }

    public function getParentId(): ?AggregateId
    {
        return $this->parentId;
    }

    public function isEqual(AggregateId $languageId): bool
    {
        return $languageId === $this->id;
    }

    public function addToLeft(int $number): void
    {
        $this->left += $number;
    }

    public function addToRight(int $number): void
    {
        $this->right += $number;
    }
}
