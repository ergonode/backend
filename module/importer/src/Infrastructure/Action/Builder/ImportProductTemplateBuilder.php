<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Builder;

use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;

/**
 */
class ImportProductTemplateBuilder implements ProductImportBuilderInterface
{
    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $query;

    /**
     * @param TemplateQueryInterface $query
     */
    public function __construct(TemplateQueryInterface $query)
    {
        $this->query = $query;
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
        $templateCode = $record->get('esa_template');
        $templateId = $this->query->findTemplateIdByCode($templateCode);

        if ($templateId) {
            $product->attributes['esa_template'] = new StringValue($templateId->getValue());

            return $product;
        }

        throw new \RuntimeException(sprintf('Template %s not exists', $templateCode));
    }
}
