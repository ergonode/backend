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

/**
 */
class Magento1TransformerGenerator implements TransformerGeneratorStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

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

        $transformer
            ->addConverter('sku', new TextConverter('sku'))
            ->addConverter('template', new TextConverter('_attribute_set'))
            ->addConverter('name', new TextConverter('name'));

        return $transformer;
    }
}
