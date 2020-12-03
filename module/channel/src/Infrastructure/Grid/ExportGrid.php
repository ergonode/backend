<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

class ExportGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $this->addColumn('status', new TextColumn('status', 'Status', new TextFilter()));
        $this->addColumn('started_at', new DateColumn('started_at', 'Started on', new DateFilter()));
        $this->addColumn('ended_at', new DateColumn('ended_at', 'Ended at', new DateFilter()));

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'CHANNEL_READ',
                'show' => ['system' => false],
                'route' => 'ergonode_channel_export',
                'parameters' => [
                    'language' => $language->getCode(),
                    'channel' => '{channel_id}',
                    'export' => '{id}',
                ],
            ],
        ]));
        $this->orderBy('started_at', 'DESC');
    }
}
