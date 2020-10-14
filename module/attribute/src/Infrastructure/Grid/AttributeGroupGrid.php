<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class AttributeGroupGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->
        addColumn(
            'elements_count',
            new IntegerColumn('elements_count', 'Number of attributes', new TextFilter())
        );

        $links = [
            'get' => [
                'privilege' => 'ATTRIBUTE_GROUP_READ',
                'route' => 'ergonode_attribute_group_read',
                'parameters' => ['language' => $language->getCode(), 'attributeGroup' => '{id}'],
            ],
            'edit' => [
                'privilege' => 'ATTRIBUTE_GROUP_UPDATE',
                'route' => 'ergonode_attribute_group_change',
                'parameters' => ['language' => $language->getCode(), 'attributeGroup' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'ATTRIBUTE_GROUP_DELETE',
                'route' => 'ergonode_attribute_group_delete',
                'parameters' => ['language' => $language->getCode(), 'attributeGroup' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ];
        $this->addColumn('_links', new LinkColumn('hal', $links));
    }
}
