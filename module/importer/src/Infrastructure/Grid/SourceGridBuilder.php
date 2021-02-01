<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\LinkColumn;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeDictionaryProvider;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Filter\NumericFilter;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class SourceGridBuilder implements GridBuilderInterface
{
    private SourceTypeDictionaryProvider $provider;

    public function __construct(SourceTypeDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();

        $types = $this->getTypes($language);

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $grid->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $grid->addColumn('type', new SelectColumn('type', 'Type', new MultiSelectFilter($types)));
        $grid->addColumn('imports', new NumericColumn('imports', 'Imports', new NumericFilter()));
        $grid->addColumn('_links', new LinkColumn('hal', [
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

        return $grid;
    }

    private function getTypes(Language $language): array
    {
        $result = [];
        foreach ($this->provider->provide($language) as $key => $label) {
            $result[] = new LabelFilterOption($key, $label);
        }

        return $result;
    }
}
