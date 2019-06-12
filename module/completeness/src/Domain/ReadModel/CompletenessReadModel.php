<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\ReadModel;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class CompletenessReadModel
{
    /**
     * @var array
     */
    private $required;

    /**
     * @var array
     */
    private $filled;

    /**
     * @var array
     */
    private $missing;

    /**
     * @var Language
     */
    private $language;

    /**
     * @param Language $language
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
        $this->filled = 0;
        $this->required = 0;
        $this->missing = [];
    }

    /**
     * @param AttributeId $id
     * @param string      $name
     * @param bool        $required
     * @param null|string $value
     */
    public function addField(AttributeId $id, string $name, bool $required, ?string $value = null): void
    {
        if ($required) {
            $this->required++;
            if ($value) {
                $this->filled++;
            } else {
                $this->missing[] = [
                    'id' => $id->getValue(),
                    'name' => $name,
                ];
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'required' => $this->required,
            'language' => $this->language->getCode(),
            'filled' => $this->filled,
            'percent' => $this->required ? round($this->filled/ $this->required * 100, 2) : 100,
            'missing' => $this->missing,
        ];
    }
}
