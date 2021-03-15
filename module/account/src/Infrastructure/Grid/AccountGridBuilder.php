<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Provider\LanguageProvider;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class AccountGridBuilder implements GridBuilderInterface
{
    private LanguageProvider $languageProvider;

    private RoleQueryInterface $roleQuery;

    public function __construct(LanguageProvider $languageProvider, RoleQueryInterface $roleQuery)
    {
        $this->languageProvider = $languageProvider;
        $this->roleQuery = $roleQuery;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $languages = $this->getLanguages($language);
        $roles = $this->getRoles();
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('email', new TextColumn('email', 'Email', new TextFilter()))
            ->addColumn('first_name', new TextColumn('first_name', 'First Name', new TextFilter()))
            ->addColumn('last_name', new TextColumn('last_name', 'Last Name', new TextFilter()))
            ->addColumn('language', new SelectColumn('language', 'Language', new MultiSelectFilter($languages)))
            ->addColumn('role_id', new SelectColumn('role_id', 'Roles', new MultiSelectFilter($roles)))
            ->addColumn('is_active', new BoolColumn('is_active', 'Activity'))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_account_user_read',
                    'parameters' => ['language' => $language->getCode(), 'user' => '{id}'],
                    'privilege' => 'ACCOUNT_GET',
                ],
                'edit' => [
                    'route' => 'ergonode_account_user_change',
                    'parameters' => ['language' => $language->getCode(), 'user' => '{id}'],
                    'privilege' => 'ACCOUNT_PUT',
                    'method' => Request::METHOD_PUT,
                ],
            ]));

        return $grid;
    }

    private function getRoles(): array
    {
        $result = [];
        foreach ($this->roleQuery->getDictionary() as $key => $value) {
            $result[] = new LabelFilterOption($key, $value);
        }

        return $result;
    }

    private function getLanguages(Language $language): array
    {
        $result = [];
        foreach ($this->languageProvider->getLanguages($language) as $code => $value) {
            $languages[] = new LabelFilterOption($code, $value);
        }

        return $result;
    }
}
