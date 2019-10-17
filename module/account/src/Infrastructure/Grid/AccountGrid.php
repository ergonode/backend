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
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class AccountGrid extends AbstractGrid
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LanguageProvider
     */
    private $languageProvider;

    /**
     * @var RoleQueryInterface
     */
    private $roleQuery;

    /**
     * @param TranslatorInterface $translator
     * @param LanguageProvider    $languageProvider
     * @param RoleQueryInterface  $roleQuery
     */
    public function __construct(TranslatorInterface $translator, LanguageProvider $languageProvider, RoleQueryInterface $roleQuery)
    {
        $this->translator = $translator;
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
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', $this->trans('Id'));
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('email', new TextColumn('email', $this->trans('Email'), new TextFilter($filters->get('email'))));
        $this->addColumn('first_name', new TextColumn('first_name', $this->trans('First Name'), new TextFilter($filters->get('first_name'))));
        $this->addColumn('last_name', new TextColumn('last_name', $this->trans('Last Name'), new TextFilter($filters->get('last_name'))));
        $this->addColumn('language', new TextColumn('language', $this->trans('Language'), new SelectFilter($languages, $filters->get('language'))));
        $this->addColumn('role_id', new TextColumn('role_id', $this->trans('Roles'), new SelectFilter($roles, $filters->get('role_id'))));
        $this->addColumn('is_active', new BoolColumn('is_active', $this->trans('Activity'), new SelectFilter($activities, $filters->get('is_active'))));
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

    /**
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, 'grid');
    }
}
