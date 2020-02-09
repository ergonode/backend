<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Importer\Infrastructure\Provider\AttributeProposalProvider;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Importer\Infrastructure\Builder\ImportConfigurationBuilder;
use Ergonode\Importer\Infrastructure\Configuration\Column\ProposalColumn;
use Ergonode\Importer\Infrastructure\Configuration\Column\AttributeColumn;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class Magento1AttributeProcessor
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var ImportConfigurationBuilder
     */
    private ImportConfigurationBuilder $builder;

    /**
     * @param AttributeRepositoryInterface $repository
     * @param ImportConfigurationBuilder   $builder
     */
    public function __construct(AttributeRepositoryInterface $repository, ImportConfigurationBuilder $builder)
    {
        $this->repository = $repository;
        $this->builder = $builder;
    }

    /**
     * @param string[] $rows
     * @param Language $language
     *
     * @return Record[]
     *
     * @throws \Exception
     */
    public function process(array $rows, Language $language): array
    {
        $result = [];
        $columns = [];
        $headers = [];
        foreach ($rows as $row) {
            foreach ($row as $key => $item) {
                if ($key[0] !== '_') {
                    $columns[$key][] = $item;
                    $headers[$key] = $key;
                }
            }
        }

        $headers = array_keys($columns);
        $configuration = $this->builder->propose($headers, $rows);

        foreach ($configuration->getColumns() as $column) {
            if($column instanceof AttributeColumn) {
                $attributeCode = new AttributeCode($column->getAttributeCode());
                $attributeId = AttributeId::fromKey($attributeCode);
                $attribute = $this->repository->load($attributeId);
                Assert::notNull($attribute);
                $record = new Record();
                $record->set('code', new StringValue($attributeCode->getValue()));
                $record->set('type', new StringValue($attribute->getType()));
                $record->set('multilingual', new StringValue('1'));
                $record->set('label', new TranslatableStringValue($attribute->getLabel()));
                if ($attribute instanceof AbstractOptionAttribute) {
                    $options = $this->getOptions($columns[$column->getField()]);
                    foreach ($options as $key => $option) {
                        $record->setValue($key, $option);
                    }
                }
                $result[] = $record;
            }
            if($column instanceof ProposalColumn) {
                $record = new Record();
                $record->set('code', new StringValue($column->getAttributeCode()));
                $record->set('type', new StringValue($column->getAttributeType()));
                $record->set('multilingual', new StringValue('1'));
                $record->set('label', new TranslatableStringValue(new TranslatableString([$language->getCode() => $column->getField()])));
                if ($column->getAttributeType() === SelectAttribute::TYPE || $column->getAttributeType() === MultiSelectAttribute::TYPE) {
                    $options = $this->getOptions($columns[$column->getField()]);
                    foreach ($options as $key => $option) {
                        $record->setValue($key, $option);
                    }
                }
                $result[] = $record;
            }
        }

        return $result;
    }

    /**
     * @param array $column
     *
     * @return AttributeOptionModel[]
     */
    private function getOptions(array $column): array
    {
        $result = [];
        $unique = array_unique($column);
        foreach ($unique as $element) {
            if($element !== '' && $element !== null) {
                $result[$element] = new StringValue($element);
            }
        }

        return $result;
    }
}