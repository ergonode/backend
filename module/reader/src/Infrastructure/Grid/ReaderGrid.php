<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class ReaderGrid extends AbstractGrid
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
        $status = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $status);
        $type = new IntegerColumn('type', 'Type', new TextFilter());
        $this->addColumn('type', $type);
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_reader_read',
                'parameters' => ['language' => $language->getCode(), 'reader' => '{id}'],
            ],
            'delete' => [
                'route' => 'ergonode_reader_delete',
                'parameters' => ['language' => $language->getCode(), 'reader' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
