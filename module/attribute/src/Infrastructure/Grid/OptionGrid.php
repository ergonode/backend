<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class OptionGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'ATTRIBUTE_GET_OPTION',
                'show' => ['system' => false],
                'route' => 'ergonode_option_read',
                'parameters' => [
                    'language' => $language->getCode(),
                    'option' => '{id}',
                    'attribute' => '{attribute_id}',
                ],
            ],
            'edit' => [
                'privilege' => 'ATTRIBUTE_PUT_OPTION',
                'show' => ['system' => false],
                'route' => 'ergonode_option_change',
                'parameters' => [
                    'language' => $language->getCode(),
                    'option' => '{id}',
                    'attribute' => '{attribute_id}',
                ],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'ATTRIBUTE_DELETE_OPTION',
                'show' => ['system' => false],
                'route' => 'ergonode_option_delete',
                'parameters' => [
                    'language' => $language->getCode(),
                    'option' => '{id}',
                    'attribute' => '{attribute_id}',
                ],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
