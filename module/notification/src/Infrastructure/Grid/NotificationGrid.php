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
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class NotificationGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', 'Id', new TextFilter($filters->get('id')));
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $column = new TextColumn('message', 'Message', new TextFilter($filters->get('message')));
        $this->addColumn('message', $column);

        $column = new DateColumn('created_at', 'Created at', new TextFilter($filters->get('created_at')));
        $this->addColumn('created_at', $column);

        $column = new DateColumn('read_at', 'Read at', new TextFilter($filters->get('read_at')));
        $this->addColumn('read_at', $column);

        $column = new TextColumn('author', 'Author', new TextFilter($filters->get('author')));
        $this->addColumn('author', $column);

        $column = new ImageColumn('avatar_id', 'Avatar');
        $this->addColumn('avatar_id', $column);
    }
}
