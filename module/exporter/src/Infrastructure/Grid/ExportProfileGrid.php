<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Exporter\Infrastructure\Provider\ExportProfileTypeDictionaryProvider;

/**
 */
class ExportProfileGrid extends AbstractGrid
{
    /**
     * @var ExportProfileTypeDictionaryProvider
     */
    private ExportProfileTypeDictionaryProvider $dictionary;

    /**
     * @param ExportProfileTypeDictionaryProvider $dictionary
     */
    public function __construct(ExportProfileTypeDictionaryProvider $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = [];
        foreach ($this->dictionary->provide($language) as $key => $value) {
            $types[] = new LabelFilterOption($key, $value);
        }

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $column = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $column);
        $column = new SelectColumn('type', 'Type', new MultiSelectFilter($types));
        $this->addColumn('type', $column);

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'EXPORT_PROFILE_READ',
                'route' => 'ergonode_export_profile_read',
                'parameters' => ['language' => $language->getCode(), 'exportProfile' => '{id}'],
            ],
            'edit' => [
                'privilege' => 'EXPORT_PROFILE_UPDATE',
                'route' => 'ergonode_export_profile_change',
                'parameters' => ['language' => $language->getCode(), 'exportProfile' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'EXPORT_PROFILE_DELETE',
                'route' => 'ergonode_export_profile_delete',
                'parameters' => ['language' => $language->getCode(), 'exportProfile' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('index', 'DESC');
    }
}
