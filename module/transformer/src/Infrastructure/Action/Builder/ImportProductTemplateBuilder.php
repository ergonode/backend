<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action\Builder;

use Ergonode\Designer\Infrastructure\Generator\DefaultTemplateGenerator;
use Ergonode\Designer\Infrastructure\Provider\TemplateProvider;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class ImportProductTemplateBuilder implements ProductImportBuilderInterface
{
    /**
     * @var TemplateProvider
     */
    private TemplateProvider $templateProvider;

    /**
     * @param TemplateProvider $templateProvider
     */
    public function __construct(TemplateProvider $templateProvider)
    {
        $this->templateProvider = $templateProvider;
    }

    /**
     * @param ImportedProduct $product
     * @param Record          $record
     *
     * @return ImportedProduct
     *
     * @throws \Exception
     */
    public function build(ImportedProduct $product, Record $record): ImportedProduct
    {
        $template = $this->templateProvider->provide(DefaultTemplateGenerator::CODE);

        $product->attributes['esa_template'] = new StringValue($template->getId()->getValue());

        return $product;
    }
}
