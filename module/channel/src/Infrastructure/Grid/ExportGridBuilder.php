<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Column\IdColumn;

class ExportGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();

        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('status', new TextColumn('status', 'Status', new TextFilter()))
            ->addColumn('started_at', new DateColumn('started_at', 'Started on', new DateFilter()))
            ->addColumn('ended_at', new DateColumn('ended_at', 'Ended at', new DateFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
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
            ]))
            ->orderBy('started_at', 'DESC');

        return $grid;
    }
}
