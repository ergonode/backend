<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class NotificationGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $notificationIdColumn = new TextColumn('id', 'Id', new TextFilter());
        $notificationIdColumn->setVisible(false);
        $grid->addColumn('id', $notificationIdColumn);

        $userIdColumn = new TextColumn('user_id', 'User Id', new TextFilter());
        $userIdColumn->setVisible(false);
        $grid->addColumn('user_id', $userIdColumn);

        $column = new TranslatableColumn('message', 'Message', 'parameters', 'notification');
        $grid->addColumn('message', $column);

        $column = new DateColumn('created_at', 'Created at', new DateFilter());
        $grid->addColumn('created_at', $column);

        $column = new DateColumn('read_at', 'Read at', new DateFilter());
        $grid->addColumn('read_at', $column);

        $column = new TextColumn('author', 'Author', new TextFilter());
        $grid->addColumn('author', $column);

        $column = new ImageColumn('avatar_filename');
        $grid->addColumn('avatar_filename', $column);

        return $grid;
    }
}
