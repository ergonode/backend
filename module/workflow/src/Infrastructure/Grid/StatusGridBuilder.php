<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Infrastructure\Grid\Filter\Option\StatusOption;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class StatusGridBuilder implements GridBuilderInterface
{
    private StatusQueryInterface $statusQuery;

    public function __construct(StatusQueryInterface $statusQuery)
    {
        $this->statusQuery = $statusQuery;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $codes = $this->getCodes($language);

        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('code', new TextColumn('code', 'System name', new TextFilter()))
            ->addColumn('status', new LabelColumn('status', 'Status', new MultiSelectFilter($codes)))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('description', new TextColumn('description', 'Description', new TextFilter()))
            ->addColumn('is_default', new BoolColumn('is_default', 'Initial status'))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_workflow_status_read',
                    'parameters' => ['language' => $language->getCode(), 'status' => '{id}'],
                    'privilege' => 'ERGONODE_ROLE_WORKFLOW_GET_STATUS',
                ],
                'edit' => [
                    'route' => 'ergonode_workflow_status_change',
                    'parameters' => ['language' => $language->getCode(), 'status' => '{id}'],
                    'privilege' => 'ERGONODE_ROLE_WORKFLOW_PUT_STATUS',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_workflow_status_delete',
                    'parameters' => ['language' => $language->getCode(), 'status' => '{id}'],
                    'privilege' => 'ERGONODE_ROLE_WORKFLOW_DELETE_STATUS',
                    'method' => Request::METHOD_DELETE,
                ],
            ]))
            ->orderBy('index', 'ASC');

        return $grid;
    }

    private function getCodes(Language $language): array
    {
        $result = [];
        foreach ($this->statusQuery->getAllStatuses($language) as $id => $status) {
            $result[] = new StatusOption($id, $status['code'], new Color($status['color']), $status['name']);
        }

        return $result;
    }
}
