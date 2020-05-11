<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Builder\LanguageTree;

use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class Branch
{
    /**
     * @var AggregateId
     */
    private AggregateId $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var int
     */
    private int $left;

    /**
     * @var int
     */
    private int $right;

    /**
     * @var AggregateId|null
     */
    private ?AggregateId $parentId;

    /**
     * @param AggregateId      $id
     * @param string           $code
     * @param int              $left
     * @param int              $right
     * @param AggregateId|null $parentId
     */
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

    /**
     * @return AggregateId
     */
    public function getId(): AggregateId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->right;
    }

    /**
     * @return AggregateId|null
     */
    public function getParentId(): ?AggregateId
    {
        return $this->parentId;
    }

    /**
     * @param AggregateId $languageId
     *
     * @return bool
     */
    public function isEqual(AggregateId $languageId): bool
    {
        return $languageId === $this->id;
    }

    /**
     * @param int $number
     */
    public function addToLeft(int $number): void
    {
        $this->left += $number;
    }

    /**
     * @param int $number
     */
    public function addToRight(int $number): void
    {
        $this->right += $number;
    }
}
