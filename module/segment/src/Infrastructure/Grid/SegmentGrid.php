<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class SegmentGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $statuses = array_combine(SegmentStatus::AVAILABLE, SegmentStatus::AVAILABLE);

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('name', 'Code', new TextFilter($filters->get('code'))));
        $this->addColumn('status', new TextColumn('status', 'Status', new SelectFilter($statuses, $filters->get('status'))));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter($filters->get('name'))));
        $this->addColumn('description', new TextColumn('description', 'Description', new TextFilter($filters->get('description'))));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_segment_read',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_segment_change',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_segment_delete',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
        $this->orderBy('id', 'DESC');
    }
}
