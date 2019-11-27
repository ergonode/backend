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
use Ergonode\Grid\Column\CheckColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Model\RequestColumn;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\AttributeColumnProvider;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusAttribute;
use Symfony\Component\HttpFoundation\Request;
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
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @var AttributeColumnProvider
     */
    private $provider;

    /**
     * @param AttributeQueryInterface      $attributeQuery
     * @param AttributeRepositoryInterface $repository
     * @param AttributeColumnProvider      $provider
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $repository,
        AttributeColumnProvider $provider
    ) {
        $this->attributeQuery = $attributeQuery;
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
        $filters = $configuration->getFilters();

        $result = [];

        /** @var RequestColumn[] $columns */
        $columns = array_merge([new RequestColumn(StatusAttribute::CODE)], $configuration->getColumns());

        $result['id'] = new CheckColumn('id', 'Id');
        $result['index'] = new IntegerColumn('index', 'Index', new TextFilter($filters->get('index')));
        $result['sku'] = new TextColumn('sku', 'Sku', new TextFilter($filters->get('sku')));
        $result['template'] = new TextColumn('template', 'Template', new TextFilter($filters->get('template')));

        foreach ($columns as $column) {
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
                $new->setEditable($attribute->isEditable());
                $new->setDeletable($attribute->isDeletable());

                if ($column->getLanguage()) {
                    $new->setLanguage($column->getLanguage());
                }

                $result[$key] = $new;
            }
        }

        $result['_links'] = new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_read',
                'parameters' => ['language' => $defaultLanguage->getCode(), 'product' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_product_change',
                'parameters' => ['language' => $defaultLanguage->getCode(), 'product' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_product_delete',
                'parameters' => ['language' => $defaultLanguage->getCode(), 'product' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]);

        return $result;
    }
}
