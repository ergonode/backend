<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Builder;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\ActionColumn;
use Ergonode\Grid\Column\CheckColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\AttributeColumnProvider;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Webmozart\Assert\Assert;

/**
 */
class ProductGridColumnBuilder
{
    /**
     * @var AttributeQueryInterface
     */
    private $attributeQuery;

    /**
     * @var StatusQueryInterface
     */
    private $statusQuery;

    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @var AttributeColumnProvider
     */
    private $provider;

    /**
     * @param AttributeQueryInterface      $attributeQuery
     * @param StatusQueryInterface         $statusQuery
     * @param AttributeRepositoryInterface $repository
     * @param AttributeColumnProvider      $provider
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        StatusQueryInterface $statusQuery,
        AttributeRepositoryInterface $repository,
        AttributeColumnProvider $provider
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->statusQuery = $statusQuery;
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $defaultLanguage
     *
     * @return array
     * @throws \Exception
     */
    public function build(GridConfigurationInterface $configuration, Language $defaultLanguage): array
    {
        $codes = $this->attributeQuery->getAllAttributeCodes();
        $statuses = $this->statusQuery->getAllStatuses($defaultLanguage);

        $filters = $configuration->getFilters();

        $statusCodes = [];
        foreach ($statuses as $code => $status) {
            $statusCodes[$code] = $status['name'];
        }

        $statusCode = AbstractProduct::STATUS;

        $result = [];
        foreach ($configuration->getColumns() as $column) {
            $code = $column->getColumn();
            $key = $column->getKey();
            $language = $column->getLanguage() ?: $defaultLanguage;
            if (in_array($code, $codes, true)) {
                $id = AttributeId::fromKey(new AttributeCode($code));
                $attribute = $this->repository->load($id);
                Assert::notNull($attribute, sprintf('Can\'t find attribute with code "%s"', $code));

                $new = $this->provider->provide($attribute, $language, $filters);
                $new->setExtension('element_id', $id->getValue());
                $new->setExtension('parameters', $attribute->getParameters());
                $new->setEditable(true);

                if ($column->getLanguage()) {
                    $new->setLanguage($column->getLanguage());
                }

                $result[$key] = $new;
            }
        }

        $result['id'] = new CheckColumn('id', 'Id');
        $result['index'] = new IntegerColumn('index', 'Index', new TextFilter($filters->getString('index')));
        $result['index']->setWidth(140);
        $result['sku'] = new TextColumn('sku', 'Sku', new TextFilter($filters->getString('sku')));
        $result[$statusCode] = new LabelColumn($statusCode, 'Status', $statuses, new SelectFilter($statusCodes, $filters->getString($statusCode)));
        $result[$statusCode]->setEditable(true);
        $result['template'] = new TextColumn('template', 'Template', new TextFilter($filters->getString('template')));
        $result['edit'] = new ActionColumn('edit', 'Edit');

        return $result;
    }
}
