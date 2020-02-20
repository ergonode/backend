<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Generator;

use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;

/**
 */
class Magento1ProductTransformerGenerator implements TransformerGeneratorStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Magento1CsvSource::TYPE;
    }

    /**
     * @param TransformerId  $transformerId
     * @param string         $name
     * @param AbstractSource $source
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
        $transformer->addField('sku', new TextConverter('sku'));
        $transformer->addField('esa_template', new TextConverter('_attribute_set'));
        $transformer->addField('esa_tree', new TextConverter('_root_category'));
        $transformer->addField('esa_categories', new JoinConverter('<_root_category>/<_category>'));
        $transformer->addField('esa_type', new TextConverter('_type'));

        // attributes
        $transformer->addAttribute(
            'image',
            ImageAttribute::TYPE,
            false,
            new TextConverter('image')
        );

        $transformer->addAttribute(
            'name',
            TextAttribute::TYPE,
            true,
            new TextConverter('name')
        );

        $transformer->addAttribute(
            'description',
            TextareaAttribute::TYPE,
            true,
            new
            TextConverter('description')
        );

        $transformer->addAttribute(
            'short_description',
            TextareaAttribute::TYPE,
            true,
            new TextConverter('short_description')
        );

        $transformer->addAttribute(
            'weight',
            NumericAttribute::TYPE,
            false,
            new TextConverter('weight')
        );

        return $transformer;
    }
}
