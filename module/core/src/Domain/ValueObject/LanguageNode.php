<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

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
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $language;

    /**
     * @var LanguageNode[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\LanguageNode>")
     */
    private array $children;

    /**
     * @param Language $language
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
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
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
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
     * @param Language $language
     *
     * @return bool
     */
    public function hasChild(Language $language): bool
    {
        foreach ($this->children as $child) {
            if ($child->language->isEqual($language)) {
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
     * @param Language $language
     *
     * @return bool
     */
    public function hasSuccessor(Language $language): bool
    {
        foreach ($this->children as $child) {
            if ($child->language->isEqual($language)) {
                return true;
            }

            if ($child->hasSuccessor($language)) {
                return true;
            }
        }

        return false;
    }
}
