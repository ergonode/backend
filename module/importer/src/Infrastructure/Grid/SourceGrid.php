<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\LinkColumn;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class SourceGrid extends AbstractGrid
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
        $name = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $name);
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'IMPORT_READ',
                'show' => ['system' => false],
                'route' => 'ergonode_source_read',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ],
            ],
            'edit' => [
                'privilege' => 'IMPORT_UPDATE',
                'show' => ['system' => false],
                'route' => 'ergonode_source_update',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'IMPORT_DELETE',
                'show' => ['system' => false],
                'route' => 'ergonode_source_delete',
                'parameters' => [
                    'language' => $language->getCode(),
                    'source' => '{id}',
                ],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
