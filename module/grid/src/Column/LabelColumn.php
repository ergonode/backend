<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Grid\FilterInterface;

/**
 */
class LabelColumn extends AbstractColumn
{
    public const TYPE = 'LABEL';

    /**
     * @var string
     */
    private $colorField;

    /**
     * @var array
     */
    private $statuses;

    /**
     * @param string               $field
     * @param string               $label
     * @param array                $statuses
     * @param FilterInterface|null $filter
     *
     * @throws \Exception
     */
    public function __construct(string $field, string $label, array $statuses, FilterInterface $filter = null)
    {
        parent::__construct($field, $label, $filter);

        $colors = [];
        foreach ($statuses as $code => $status) {
            $colors[$code] = $status['color'];
        }
        $this->setExtension('attributeId', AttributeId::fromKey(new AttributeCode($field))->getValue());
        $this->setExtension('colors', $colors);
        $this->statuses = $statuses;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $id
     * @param array  $row
     *
     * @return array
     */
    public function render(string $id, array $row): array
    {
        if (isset($row[$id])) {
            $status = $this->statuses[$row[$id]];

            return ['label' => $status['name'], 'color' => $status['color']];
        }

        return [];
    }
}
