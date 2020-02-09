<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Generator;

use Ergonode\Importer\Application\Model\Form\ConfigurationModel;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Transformer\Infrastructure\Converter\MultilingualTextConverter;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
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
     * @param TransformerId      $transformerId
     * @param string             $name
     * @param ConfigurationModel $configuration
     *
     * @return Transformer
     *
     * @throws \Exception
     */
    public function generate(
        TransformerId $transformerId,
        string $name,
        ConfigurationModel $configuration
    ): Transformer {
        $transformer = new Transformer($transformerId, $name, $name);

        $transformer->addConverter('sku', new TextConverter('sku'));
        $transformer->addConverter('template', new TextConverter('_attribute_set'));
        $transformer->addConverter('categories', new TextConverter('_category'));

        // system
        $this->addAttribute($transformer, new AttributeCode('name'));
        $this->addAttribute($transformer, new AttributeCode('description'));
        $this->addAttribute($transformer, new AttributeCode('short_description'));
        $this->addAttribute($transformer, new AttributeCode('weight'));

        // custom

        return $transformer;
    }

    /**
     * @param Transformer   $transformer
     * @param AttributeCode $code
     *
     * @return Transformer
     *
     * @throws \Exception
     */
    public function addAttribute(Transformer $transformer, AttributeCode $code): Transformer
    {
        $attributeId = AttributeId::fromKey($code);
        $attribute = $this->repository->load($attributeId);

        if ($attribute) {
            if ($attribute->isMultilingual()) {
                $converter = new MultilingualTextConverter([Language::EN => $code->getValue()]);
            } else {
                $converter = new TextConverter($code->getValue());
            }

            $transformer->addConverter($code->getValue(), $converter, 'values');
        }

        return $transformer;
    }
}
