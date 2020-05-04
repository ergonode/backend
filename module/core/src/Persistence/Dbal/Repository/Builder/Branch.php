<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Repository\Builder;

use Ramsey\Uuid\Uuid;

/**
 */
class Branch
{
    /**
     * @var Uuid
     */
    private Uuid $id;

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
     * @var Uuid|null
     */
    private ?Uuid $parentId;

    /**
     * @var string|null
     */
    private ?string $parentCode;

    /**
     * @param Uuid        $id
     * @param string      $code
     * @param int         $left
     * @param int         $right
     * @param Uuid|null   $parentId
     * @param string|null $parentCode
     */
    public function __construct(
        Uuid $id,
        string $code,
        int $left,
        int $right,
        ?Uuid $parentId = null,
        ?string $parentCode = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->left = $left;
        $this->right = $right;
        $this->parentId = $parentId;
        $this->parentCode = $parentCode;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
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
     * @return Uuid|null
     */
    public function getParentId(): ?Uuid
    {
        return $this->parentId;
    }

    /**
     * @return string|null
     */
    public function getParentCode(): ?string
    {
        return $this->parentCode;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isEqualCode(string $code): bool
    {
        return $code === $this->code;
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
