<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Provider\LanguageProvider;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class AccountGrid extends AbstractGrid
{
    /**
     * @var LanguageProvider
     */
    private LanguageProvider $languageProvider;

    /**
     * @var RoleQueryInterface
     */
    private RoleQueryInterface $roleQuery;

    /**
     * @param LanguageProvider   $languageProvider
     * @param RoleQueryInterface $roleQuery
     */
    public function __construct(LanguageProvider $languageProvider, RoleQueryInterface $roleQuery)
    {
        $this->languageProvider = $languageProvider;
        $this->roleQuery = $roleQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $languages = $this->languageProvider->getLanguages($language);
        $roles = $this->roleQuery->getDictionary();
        $activities = [1 => 'Active', 0 => 'In active'];

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('email', new TextColumn('email', 'Email', new TextFilter()));
        $this->addColumn('first_name', new TextColumn('first_name', 'First Name', new TextFilter()));
        $this->addColumn('last_name', new TextColumn('last_name', 'Last Name', new TextFilter()));
        $this->addColumn('language', new TextColumn('language', 'Language', new MultiSelectFilter($languages)));
        $this->addColumn('role_id', new TextColumn('role_id', 'Roles', new MultiSelectFilter($roles)));
        $this->addColumn('is_active', new BoolColumn('is_active', 'Activity'));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_account_user_read',
                'parameters' => ['language' => $language->getCode(), 'user' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_account_user_change',
                'parameters' => ['language' => $language->getCode(), 'user' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
        ]));

        $this->setConfiguration(AbstractGrid::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
