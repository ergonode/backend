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
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\AttributeColumnProvider;
use Webmozart\Assert\Assert;

/**
 */
class ProductGridColumnBuilder
{
    /**
     * @var AttributeQueryInterface
     */
    private $query;

    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @var AttributeColumnProvider
     */
    private $provider;

    /**
     * @param AttributeQueryInterface      $query
     * @param AttributeRepositoryInterface $repository
     * @param AttributeColumnProvider      $provider
     */
    public function __construct(
        AttributeQueryInterface $query,
        AttributeRepositoryInterface $repository,
        AttributeColumnProvider $provider
    ) {
        $this->query = $query;
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
        $codes = $this->query->getAllAttributeCodes();

        $filters = $configuration->getFilters();

        $result = [];
        $result['id'] = new CheckColumn('id', 'Id');
        $result['index'] = new IntegerColumn('index', 'Index', new TextFilter($filters->getString('index')));
        $result['index']->setWidth(140);
        $result['sku'] = new TextColumn('sku', 'Sku', new TextFilter($filters->getString('sku')));
        $result['template'] = new TextColumn('template', 'Template', new TextFilter($filters->getString('template')));
        $result['edit'] = new ActionColumn('edit', 'Edit');

        foreach ($configuration->getColumns() as $column) {
            $code = $column->getColumn();
            $key = $column->getKey();
            $language = $column->getLanguage() ?: $defaultLanguage;
            if (in_array($code, $codes, true)) {
                $id = AttributeId::fromKey(new AttributeCode($code));
                $attribute = $this->repository->load($id);
                Assert::notNull($attribute, sprintf('Can\'t find attribute witch code %s', $code));

                $new = $this->provider->provide($attribute, $language, $filters);
                if ($column->getLanguage()) {
                    $new->setLanguage($column->getLanguage());
                }
                $new->setExtension('element_id', $id->getValue());
                $new->setExtension('parameters', $attribute->getParameters());

                $new->setEditable(true);
                $result[$key] = $new;
            }
        }

        return $result;
    }
}
