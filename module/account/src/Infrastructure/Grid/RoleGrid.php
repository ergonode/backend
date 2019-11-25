<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\TextAreaColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class RoleGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter($filters->get('name'))));
        $this->addColumn('description', new TextAreaColumn('description', 'Description', new TextFilter($filters->get('description'))));
        $this->addColumn('users_count', new NumericColumn('users_count', 'Users', new TextFilter($filters->get('users_count'))));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_account_role_read',
                'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_account_role_change',
                'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_account_role_delete',
                'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));

        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
