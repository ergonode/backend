<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class ProductAttributeLanguageResolver
{
    private LanguageQueryInterface $query;

    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    public function resolve(AbstractAttribute $attribute, Language $language): Language
    {
        if ($attribute->getScope()->isGlobal()) {
            return $this->query->getRootLanguage();
        }

        return $language;
    }
}
