<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Importer\Infrastructure\Provider\SourceTypeDictionaryProvider;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Filter\NumericFilter;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;
use Ergonode\Grid\Action\GetAction;
use Ergonode\Grid\Action\DeleteAction;
use Ergonode\Grid\Action\PutAction;

class SourceGridBuilder implements GridBuilderInterface
{
    private SourceTypeDictionaryProvider $provider;

    public function __construct(SourceTypeDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $types = $this->getTypes($language);

        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('type', new SelectColumn('type', 'Type', new MultiSelectFilter($types)))
            ->addColumn('imports', new NumericColumn('imports', 'Imports', new NumericFilter()))
            ->addAction('get', new GetAction(
                'ergonode_source_read',
                'IMPORT_READ',
                [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ]
            ))
            ->addAction('edit', new PutAction(
                'ergonode_source_update',
                'IMPORT_UPDATE',
                [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ]
            ))->addAction('delete', new DeleteAction(
                'ergonode_source_delete',
                'IMPORT_DELETE',
                [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ]
            ));

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
