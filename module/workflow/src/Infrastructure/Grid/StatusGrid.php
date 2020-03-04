<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class StatusGrid extends AbstractGrid
{
    /**
     * @var StatusQueryInterface
     */
    private StatusQueryInterface $statusQuery;

    /**
     * @param StatusQueryInterface $statusQuery
     */
    public function __construct(StatusQueryInterface $statusQuery)
    {
        $this->statusQuery = $statusQuery;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     *
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $statuses = $this->statusQuery->getAllStatuses($language);

        $codes = [];
        foreach ($statuses as $id => $status) {
            $codes[$id] = $status['name'];
        }

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('status', new LabelColumn('status', 'Status', $statuses, new SelectFilter($codes)));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('description', new TextColumn('description', 'Description', new TextFilter()));
        $this->addColumn('is_default', new BoolColumn('is_default', 'Initial status'));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_workflow_status_read',
                'parameters' => ['language' => $language->getCode(), 'status' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_workflow_status_change',
                'parameters' => ['language' => $language->getCode(), 'status' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_workflow_status_delete',
                'parameters' => ['language' => $language->getCode(), 'status' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));

        $this->orderBy('code', 'DESC');
        $this->setConfiguration(AbstractGrid::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
