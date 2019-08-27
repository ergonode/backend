<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Checker;

use Ergonode\Designer\Domain\Checker\TemplateRelationCheckerInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Product\Domain\Query\ProductQueryInterface;

/**
 */
class TemplateProductRelationChecker implements TemplateRelationCheckerInterface
{
    public const TYPE = 'product';

    /**
     * @var ProductQueryInterface
     */
    private $query;

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param Template $template
     *
     * @return array
     */
    public function getRelations(Template $template): array
    {
        return $this->query->findProductIdByTemplateId($template->getId());
    }
}
