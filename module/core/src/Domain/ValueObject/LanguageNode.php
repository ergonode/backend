<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use JMS\Serializer\Annotation as JMS;

class LanguageNode
{
    /**
     * @JMS\Exclude()
     */
    private ?LanguageNode $parent;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\LanguageId")
     */
    private LanguageId $languageId;

    /**
     * @var LanguageNode[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\LanguageNode>")
     */
    private array $children;

    public function __construct(LanguageId $languageId)
    {
        $this->languageId = $languageId;
        $this->children = [];
    }

    public function getParent(): ?LanguageNode
    {
        return $this->parent;
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
        $child->setParent($this);
    }

    public function setParent(?LanguageNode $parent = null): void
    {
        $this->parent = $parent;
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
