<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Filter;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Infrastructure\Validator\AttributeImportValidator;

class AttributeImportFilter
{
    private AttributeQueryInterface $attributeQuery;

    private AttributeImportValidator $attributeImportValidator;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeImportValidator $attributeImportValidator
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeImportValidator = $attributeImportValidator;
    }


    /**
     * @var TranslatableString[]
     */
    public function filter(array $attributes): array
    {
        $attributesToRedispatch = [];
        foreach ($attributes as $codeValue => $attribute) {
            $code = new AttributeCode($codeValue);
            $attributeType = $this->attributeQuery->getAttributeTypeByCode($code);

            if (!$attributeType) {
                throw new ImportException(
                    'Attribute with {code} code does not exist',
                    ['{code}' => $code->getValue()]
                );
            }

            if (!$this->attributeImportValidator->validate($attributeType, $code, $attribute)) {
                $attributesToRedispatch[$codeValue] = $attribute;
            }
        }

        return $attributesToRedispatch;
    }
}
