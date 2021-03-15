<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\ReadModel;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;

class CompletenessReadModel
{
    /**
     * @JMS\Type("integer")
     */
    private int $required;

    /**
     * @JMS\Type("integer")
     */
    private int $filled;

    /**
     * @var array
     *
     * @JMS\Type("array<Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel>")
     */
    private array $missing;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
        $this->filled = 0;
        $this->required = 0;
        $this->missing = [];
    }

    public function addCompletenessElement(CompletenessElementReadModel $model): void
    {
        if ($model->isRequired()) {
            $this->required++;
            if ($model->isFilled()) {
                $this->filled++;
            } else {
                $this->missing[] = $model;
            }
        }
    }

    public function getRequired(): int
    {
        return $this->required;
    }

    public function getFilled(): int
    {
        return $this->filled;
    }

    /**
     * @return array
     */
    public function getMissing(): array
    {
        return $this->missing;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @JMS\VirtualProperty(name="percent")
     */
    public function getPercent(): float
    {
        return $this->required ? round($this->filled/ $this->required * 100, 2) : 100;
    }
}
