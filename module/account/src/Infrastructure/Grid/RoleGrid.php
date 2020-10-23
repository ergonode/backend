<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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

class RoleGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('description', new TextAreaColumn('description', 'Description', new TextFilter()));
        $this->addColumn('users_count', new NumericColumn('users_count', 'Users', new TextFilter()));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_account_role_read',
                'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                'privilege' => 'USER_ROLE_READ',
            ],
            'edit' => [
                'route' => 'ergonode_account_role_change',
                'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                'privilege' => 'USER_ROLE_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_account_role_delete',
                'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                'privilege' => 'USER_ROLE_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
