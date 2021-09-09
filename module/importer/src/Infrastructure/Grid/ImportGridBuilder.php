<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
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
use Ergonode\Grid\Column\IdColumn;

class ImportGridBuilder implements GridBuilderInterface
{
    private ImportStatusDictionary $dictionary;

    public function __construct(ImportStatusDictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $status = $this->getStatus($language);

        $grid = new Grid();


        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('created_at', new DateTimeColumn('created_at', 'Created at', new DateTimeFilter()))
            ->addColumn('started_at', new DateTimeColumn('started_at', 'Started on', new DateTimeFilter()))
            ->addColumn('ended_at', new DateTimeColumn('ended_at', 'Ended at', new DateTimeFilter()))
            ->addColumn('records', new IntegerColumn('records', 'Records', new TextFilter()))
            ->addColumn('status', new SelectColumn('status', 'Status', new MultiSelectFilter($status)))
            ->addColumn('errors', new IntegerColumn('errors', 'Errors', new TextFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'privilege' => 'ERGONODE_ROLE_IMPORT_GET_GRID',
                    'show' => ['system' => false],
                    'route' => 'ergonode_import_read',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'source' => '{source_id}',
                        'import' => '{id}',
                    ],
                ],
            ]))
            ->orderBy('created_at', 'DESC');

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
