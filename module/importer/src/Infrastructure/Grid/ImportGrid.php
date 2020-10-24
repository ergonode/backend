<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
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

class ImportGrid extends AbstractGrid
{
    private ImportStatusDictionary $dictionary;

    public function __construct(ImportStatusDictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $status = [];
        foreach ($this->dictionary->getDictionary($language) as $key => $value) {
            $status[] = new LabelFilterOption($key, $value);
        }

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $createdAt = new DateColumn('created_at', 'Created at', new DateFilter());
        $this->addColumn('created_at', $createdAt);
        $startedAt = new DateColumn('started_at', 'Started on', new DateFilter());
        $this->addColumn('started_at', $startedAt);
        $endedAt = new DateColumn('ended_at', 'Ended at', new DateFilter());
        $this->addColumn('ended_at', $endedAt);
        $records = new IntegerColumn('records', 'Records', new TextFilter());
        $this->addColumn('records', $records);
        $status = new SelectColumn('status', 'Status', new MultiSelectFilter($status));
        $this->addColumn('status', $status);
        $errors = new IntegerColumn('errors', 'Errors', new TextFilter());
        $this->addColumn('errors', $errors);
        $this->addColumn('_links', new LinkColumn('hal', [
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
    }
}
