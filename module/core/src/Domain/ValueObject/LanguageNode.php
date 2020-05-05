<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class LanguageNode
{
    /**
     * @var LanguageNode|null
     *
     * @JMS\Exclude()
     */
    private ?LanguageNode $parent;

    /**
     * @var AggregateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\AggregateId")
     */
    private AggregateId $languageId;

    /**
     * @var LanguageNode[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\LanguageNode>")
     */
    private array $children;

    /**
     * @param AggregateId $languageId
     */
    public function __construct(AggregateId $languageId)
    {
        $this->languageId = $languageId;
        $this->children = [];
    }

    /**
     * @return LanguageNode|null
     */
    public function getParent(): ?LanguageNode
    {
        return $this->parent;
    }

    /**
     * @return AggregateId
     */
    public function getLanguageId(): AggregateId
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

    /**
     * @param LanguageNode $child
     */
    public function addChild(LanguageNode $child): void
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    /**
     * @param LanguageNode|null $parent
     */
    public function setParent(?LanguageNode $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * @param AggregateId $languageId
     *
     * @return bool
     */
    public function hasChild(AggregateId $languageId): bool
    {
        foreach ($this->children as $child) {
            if ($child->languageId->isEqual($languageId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * @param AggregateId $languageId
     *
     * @return bool
     */
    public function hasSuccessor(AggregateId $languageId): bool
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
