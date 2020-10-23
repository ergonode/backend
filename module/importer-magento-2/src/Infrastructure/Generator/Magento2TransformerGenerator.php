<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento2\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;

class Magento2TransformerGenerator implements TransformerGeneratorStrategyInterface
{
    private AttributeRepositoryInterface $repository;

    public function getType(): string
    {
        return Magento2CsvSource::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function generate(
        TransformerId $transformerId,
        string $name,
        AbstractSource $source
    ): Transformer {
        $transformer = new Transformer($transformerId, $name, $name);

        $transformer
            ->addField('sku', new TextConverter('sku'))
            ->addField('template', new TextConverter('attribute_set_code'))
            ->addField('name', new TextConverter('name'));

//        foreach ($configuration->columns as $column) {
//            $attributeCode = new AttributeCode($column->getField());
//            $attributeId = AttributeId::fromKey($attributeCode);
//            $attribute = $this->repository->load($attributeId);
//            Assert::notNull($attribute);
//        }

        return $transformer;
    }
}
