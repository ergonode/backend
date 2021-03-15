<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy\ImportProductAttributeStrategyInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class ImportProductAttributeBuilder
{
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var ImportProductAttributeStrategyInterface[]
     */
    private iterable $strategies;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        iterable $strategies
    ) {
        $this->attributeQuery = $attributeQuery;
        Assert::allIsInstanceOf($strategies, ImportProductAttributeStrategyInterface::class);
        $this->strategies = $strategies;
    }

    /**
     * @param TranslatableString[] $attributes
     *
     * @return ValueInterface[]
     */
    public function build(array $attributes): array
    {
        $result = [];
        foreach ($attributes as $code => $value) {
            $code = new AttributeCode($code);
            $id = $this->attributeQuery->findAttributeIdByCode($code);
            if (null === $id) {
                throw new ImportException('Missing {code} attribute.', ['{code}' => $code]);
            }
            $type = $this->attributeQuery->findAttributeType($id);
            Assert::notNull($type, sprintf('Attribute type %s not exists', $code));

            $result[$code->getValue()] = null;

            $strategy = $this->getStrategy($type);
            if ($strategy) {
                $result[$code->getValue()] = $strategy->build($id, $code, $value);
            } else {
                $result[$code->getValue()] = $this->buildDefault($value);
            }
        }

        return $result;
    }

    public function buildDefault(TranslatableString $value): TranslatableStringValue
    {
        $translation = [];
        foreach ($value as $key => $version) {
            if ('' !== $version && null !== $version) {
                $translation[$key] = $version;
            }
        }

        return new TranslatableStringValue(new TranslatableString($translation));
    }

    private function getStrategy(AttributeType $type): ?ImportProductAttributeStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($type)) {
                return $strategy;
            }
        }

        return null;
    }
}
