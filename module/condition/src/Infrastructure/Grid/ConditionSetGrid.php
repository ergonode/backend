<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class ConditionSetGrid extends AbstractGrid
{
    /**
     * {@inheritDoc}
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter($filters->get('code'))));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_designer_template_read',
                'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_designer_template_change',
                'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_designer_template_delete',
                'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
