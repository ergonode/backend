<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class NotificationGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $userIdColumn = new TextColumn('user_id', 'User Id', new TextFilter());
        $userIdColumn->setVisible(false);

        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('user_id', $userIdColumn)
            ->addColumn(
                'message',
                new TranslatableColumn('message', 'Message', 'parameters', 'notification')
            )
            ->addColumn('type', new TextColumn('type', 'Type', new TextFilter()))
            ->addColumn('object_id', new TextColumn('object_id', 'Object ID', new TextFilter()))
            ->addColumn('created_at', new DateTimeColumn('created_at', 'Created at', new DateTimeFilter()))
            ->addColumn('read_at', new DateTimeColumn('read_at', 'Read at', new DateTimeFilter()))
            ->addColumn('author', new TextColumn('author', 'Author', new TextFilter()))
            ->addColumn('avatar_filename', new ImageColumn('avatar_filename'));

        return $grid;
    }
}
