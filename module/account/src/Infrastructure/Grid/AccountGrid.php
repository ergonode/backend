<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Provider\LanguageProvider;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ActionColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
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
    private $provider;

    /**
     * @param TranslatorInterface $translator
     * @param LanguageProvider    $provider
     */
    public function __construct(TranslatorInterface $translator, LanguageProvider $provider)
    {
        $this->translator = $translator;
        $this->provider = $provider;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $languages = $this->provider->getLanguages($language);
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', $this->trans('Id'));
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('email', new TextColumn('email', $this->trans('Email'), new TextFilter($filters->getString('email'))));
        $this->addColumn('first_name', new TextColumn('first_name', $this->trans('First Name'), new TextFilter($filters->getString('first_name'))));
        $this->addColumn('last_name', new TextColumn('last_name', $this->trans('Last Name'), new TextFilter($filters->getString('last_name'))));
        $this->addColumn('language', new TextColumn('language', $this->trans('Language'), new SelectFilter($languages, $filters->getString('language'))));
        $this->addColumn('edit', new ActionColumn('edit'));
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
