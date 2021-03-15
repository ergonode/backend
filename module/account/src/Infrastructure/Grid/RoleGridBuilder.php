<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\TextAreaColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Grid;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\Column\IdColumn;

class RoleGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('description', new TextAreaColumn('description', 'Description', new TextFilter()))
            ->addColumn('users_count', new NumericColumn('users_count', 'Users', new TextFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_account_role_read',
                    'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                    'privilege' => 'ACCOUNT_GET_ROLE',
                ],
                'edit' => [
                    'route' => 'ergonode_account_role_change',
                    'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                    'privilege' => 'ACCOUNT_PUT_ROLE',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_account_role_delete',
                    'parameters' => ['language' => $language->getCode(), 'role' => '{id}'],
                    'privilege' => 'ACCOUNT_DELETE_ROLE',
                    'method' => Request::METHOD_DELETE,
                ],
            ]));

        return $grid;
    }
}
