<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Importer\Infrastructure\Dictionary\ImportStatusDictionary;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class ImportGridBuilder implements GridBuilderInterface
{
    private ImportStatusDictionary $dictionary;

    public function __construct(ImportStatusDictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $status = $this->getStatus($language);

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $createdAt = new DateColumn('created_at', 'Created at', new DateFilter());
        $grid->addColumn('created_at', $createdAt);
        $startedAt = new DateColumn('started_at', 'Started on', new DateFilter());
        $grid->addColumn('started_at', $startedAt);
        $endedAt = new DateColumn('ended_at', 'Ended at', new DateFilter());
        $grid->addColumn('ended_at', $endedAt);
        $records = new IntegerColumn('records', 'Records', new TextFilter());
        $grid->addColumn('records', $records);
        $status = new SelectColumn('status', 'Status', new MultiSelectFilter($status));
        $grid->addColumn('status', $status);
        $errors = new IntegerColumn('errors', 'Errors', new TextFilter());
        $grid->addColumn('errors', $errors);
        $grid->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'IMPORT_READ',
                'show' => ['system' => false],
                'route' => 'ergonode_import_read',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{source_id}',
                    'import' => '{id}',
                ],
            ],
        ]));

        $grid->orderBy('created_at', 'DESC');

        return $grid;
    }

    private function getStatus(Language $language): array
    {
        $status = [];
        foreach ($this->dictionary->getDictionary($language) as $key => $value) {
            $status[] = new LabelFilterOption($key, $value);
        }

        return $status;
    }
}
