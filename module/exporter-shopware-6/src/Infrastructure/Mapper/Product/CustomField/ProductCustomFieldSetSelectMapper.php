<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterOptionValueException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\AbstractProductCustomFieldSetMapper;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProductCustomFieldSetSelectMapper extends AbstractProductCustomFieldSetMapper
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
        return SelectAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Shopware6ExporterOptionValueException
     */
    protected function getValue(Shopware6Channel $channel, AbstractAttribute $attribute, $calculateValue): string
    {
        $options = explode(',', $calculateValue);

        foreach ($options as $optionValue) {
            $optionId = new AggregateId($optionValue);
            $option = $this->optionRepository->load($optionId);
            if ($option) {
                return $option->getCode()->getValue();
            }
        }

        throw new Shopware6ExporterOptionValueException($attribute->getCode());
    }
}
