<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ImporterMagento1\Infrastructure\Converter\Magento1CategoryConverter;

class Magento1ProductTransformerGenerator implements TransformerGeneratorStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Magento1CsvSource::TYPE;
    }

    /**
     * @param TransformerId                    $transformerId
     * @param string                           $name
     * @param AbstractSource|Magento1CsvSource $source
     *
     * @return Transformer
     *
     * @throws \Exception
     */
    public function generate(
        TransformerId $transformerId,
        string $name,
        AbstractSource $source
    ): Transformer {
        $transformer = new Transformer($transformerId, $name, $name);

        // system
        $transformer->addField('esa_categories', new Magento1CategoryConverter());
        $transformer->addField('bindings', new TextConverter('_super_attribute_code'));
        $transformer->addField('variants', new TextConverter('_super_products_sku'));
        $transformer->addField('relations', new TextConverter('_associated_sku'));

        // attributes
        foreach ($source->getAttributes() as $code => $attributeId) {
            $attribute = $this->repository->load($attributeId);
            if ($attribute) {
                $transformer->addAttribute(
                    $attribute->getCode()->getValue(),
                    $attribute->getType(),
                    $attribute->isMultilingual(),
                    new
                    TextConverter($code)
                );
            }
        }

        return $transformer;
    }
}
