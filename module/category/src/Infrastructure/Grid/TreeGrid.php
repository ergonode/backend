<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class TreeGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $code = new TextColumn('code', 'Code', new TextFilter());
        $this->addColumn('code', $code);
        $this->orderBy('code', 'ASC');
        $name = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $name);
        $this->addColumn('_links', new LinkColumn('', [
            'get' => [
                'route' => 'ergonode_category_tree_read',
                'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_category_tree_change',
                'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_category_tree_delete',
                'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('name', 'ASC');
    }
}
