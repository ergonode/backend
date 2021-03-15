<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
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
            ->addColumn('started_at', new DateTimeColumn('started_at', 'Started on', new DateTimeFilter()))
            ->addColumn('ended_at', new DateTimeColumn('ended_at', 'Ended at', new DateTimeFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'privilege' => 'CHANNEL_GET_EXPORT',
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
