<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use Symfony\Component\HttpFoundation\Request;

/**
 */
class ImportGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $status = new TextColumn('status', 'Status', new TextFilter());
        $this->addColumn('status', $status);
        $index = new IntegerColumn('lines', 'Lines', new TextFilter());
        $this->addColumn('lines', $index);
        $createdAt = new DateColumn('created_at', 'Created at', new DateFilter());
        $this->addColumn('created_at', $createdAt);
        $startedAt = new DateColumn('started_at', 'Started at', new DateFilter());
        $this->addColumn('started_at', $startedAt);
        $endedAt = new DateColumn('ended_at', 'Ended at', new DateFilter());
        $this->addColumn('ended_at', $endedAt);
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
