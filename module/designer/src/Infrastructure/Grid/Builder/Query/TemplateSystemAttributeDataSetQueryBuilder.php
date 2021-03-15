<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

class TemplateSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof TemplateSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $sql = sprintf('p.template_id AS "%s"', $key);

        $query->addSelect($sql);
    }
}
