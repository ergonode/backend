<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Query\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;

/**
 */
class TemplateGetProductQueryDecorator implements GetProductQueryInterface
{
    /**
     * @var GetProductQueryInterface
     */
    private $query;

    /**
     * @var TemplateQueryInterface
     */
    private $templateQuery;

    /**
     * @param GetProductQueryInterface $query
     * @param TemplateQueryInterface   $templateQuery
     */
    public function __construct(GetProductQueryInterface $query, TemplateQueryInterface $templateQuery)
    {
        $this->query = $query;
        $this->templateQuery = $templateQuery;
    }

    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return array
     *
     * @throws \Exception
     */
    public function query(ProductId $productId, Language $language): array
    {
        $template = $this->templateQuery->findProductTemplateId($productId);

        $result = $this->query->query($productId, $language);
        $result['design_template_id'] = $template->getValue();

        return $result;
    }
}
