<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

class NotificationGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $notificationIdColumn = new TextColumn('id', 'Id', new TextFilter());
        $notificationIdColumn->setVisible(false);
        $this->addColumn('id', $notificationIdColumn);

        $userIdColumn = new TextColumn('user_id', 'User Id', new TextFilter());
        $userIdColumn->setVisible(false);
        $this->addColumn('user_id', $userIdColumn);

        $column = new TranslatableColumn('message', 'Message', 'parameters', 'notification');
        $this->addColumn('message', $column);

        $column = new DateColumn('created_at', 'Created at', new DateFilter());
        $this->addColumn('created_at', $column);

        $column = new DateColumn('read_at', 'Read at', new DateFilter());
        $this->addColumn('read_at', $column);

        $column = new TextColumn('author', 'Author', new TextFilter());
        $this->addColumn('author', $column);

        $column = new ImageColumn('avatar_filename');
        $this->addColumn('avatar_filename', $column);
    }
}
