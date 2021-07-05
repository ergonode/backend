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
use Ergonode\Importer\Infrastructure\Validator\AttributeToRedispatchImportValidator;

class AttributeToRedispatchImportFilter
{
    private AttributeQueryInterface $attributeQuery;

    private AttributeToRedispatchImportValidator $attributeToRedispatchImportValidator;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeToRedispatchImportValidator $attributeToRedispatchImportValidator
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeToRedispatchImportValidator = $attributeToRedispatchImportValidator;
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

            if (!$this->attributeToRedispatchImportValidator->validate($attributeType, $code, $attribute)) {
                $attributesToRedispatch[$codeValue] = $attribute;
            }
        }

        return $attributesToRedispatch;
    }
}
