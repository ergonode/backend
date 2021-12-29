<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;

class LanguageNode
{
    private LanguageId $languageId;

    /**
     * @var LanguageNode[]
     */
    private array $children;

    public function __construct(LanguageId $languageId)
    {
        $this->languageId = $languageId;
        $this->children = [];
    }

    public function getLanguageId(): LanguageId
    {
        return $this->languageId;
    }

    /**
     * @return LanguageNode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(LanguageNode $child): void
    {
        $this->children[] = $child;
    }

    public function hasChild(LanguageId $languageId): bool
    {
        foreach ($this->children as $child) {
            if ($child->languageId->isEqual($languageId)) {
                return true;
            }
        }

        return false;
    }

    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    public function hasSuccessor(LanguageId $languageId): bool
    {
        foreach ($this->children as $child) {
            if ($child->languageId->isEqual($languageId)) {
                return true;
            }

            if ($child->hasSuccessor($languageId)) {
                return true;
            }
        }

        return false;
    }
}
