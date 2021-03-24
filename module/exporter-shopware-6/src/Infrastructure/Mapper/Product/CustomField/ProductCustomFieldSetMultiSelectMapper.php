<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\AbstractProductCustomFieldSetMapper;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProductCustomFieldSetMultiSelectMapper extends AbstractProductCustomFieldSetMapper
{
    private OptionRepositoryInterface $optionRepository;

    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator,
        OptionRepositoryInterface $optionRepository
    ) {
        parent::__construct($repository, $calculator);
        $this->optionRepository = $optionRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return MultiSelectAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValue(Shopware6Channel $channel, AbstractAttribute $attribute, $calculateValue): array
    {
        $options = explode(',', $calculateValue);
        $result = [];
        foreach ($options as $optionValue) {
            $optionId = new AggregateId($optionValue);
            $option = $this->optionRepository->load($optionId);
            if ($option) {
                $result[] = $option->getCode()->getValue();
            }
        }

        return $result;
    }
}
