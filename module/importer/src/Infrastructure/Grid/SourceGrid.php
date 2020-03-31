<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\LinkColumn;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeDictionaryProvider;

/**
 */
class SourceGrid extends AbstractGrid
{
    /**
     * @var SourceTypeDictionaryProvider
     */
    private SourceTypeDictionaryProvider $provider;

    /**
     * @param SourceTypeDictionaryProvider $provider
     */
    public function __construct(SourceTypeDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = $this->provider->provide($language);

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('type', new SelectColumn('type', 'Type', new MultiSelectFilter($types)));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'IMPORT_READ',
                'show' => ['system' => false],
                'route' => 'ergonode_source_read',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ],
            ],
            'edit' => [
                'privilege' => 'IMPORT_UPDATE',
                'show' => ['system' => false],
                'route' => 'ergonode_source_update',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'IMPORT_DELETE',
                'show' => ['system' => false],
                'route' => 'ergonode_source_delete',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
