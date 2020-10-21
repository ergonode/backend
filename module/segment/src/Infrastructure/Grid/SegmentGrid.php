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
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\TextColumn;
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
        $statuses = array_combine(SegmentStatus::AVAILABLE, SegmentStatus::AVAILABLE);

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('name', 'System name', new TextFilter()));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('description', new TextColumn('description', 'Description', new TextFilter()));
        $this->addColumn('status', new TextColumn('status', 'Status', new TextFilter()));
        $this->addColumn('products_count', new NumericColumn('products_count', 'Products ', new TextFilter()));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_segment_read',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'privilege' => 'SEGMENT_READ',
            ],
            'edit' => [
                'route' => 'ergonode_segment_change',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'privilege' => 'SEGMENT_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_segment_delete',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'privilege' => 'SEGMENT_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('id', 'DESC');
    }
}
