<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Infrastructure\Grid\Filter\Option\StatusOption;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Grid\Column\TextColumn;

class TransitionGridBuilder implements GridBuilderInterface
{
    private StatusQueryInterface $statusQuery;

    private RoleQueryInterface $roleQuery;

    public function __construct(StatusQueryInterface $statusQuery, RoleQueryInterface $roleQuery)
    {
        $this->statusQuery = $statusQuery;
        $this->roleQuery = $roleQuery;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $codes = $this->getStatuses($language);
        $roles = $this->getRoles();

        $grid = new Grid();

        $conditionColumn = new TextColumn('condition_set_id', 'ConditionSet');
        $conditionColumn->setVisible(false);

        $grid
            ->addColumn('from', new LabelColumn('from', 'From', new MultiSelectFilter($codes)))
            ->addColumn('to', new LabelColumn('to', 'To', new MultiSelectFilter($codes)))
            ->addColumn('roles', new MultiSelectColumn('roles', 'Roles', new MultiSelectFilter($roles)))
            ->addColumn('condition_set_id', $conditionColumn)
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_workflow_transition_read',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'from' => '{from}',
                        'to' => '{to}',
                    ],
                    'privilege' => 'ERGONODE_ROLE_WORKFLOW_GET_TRANSITION',
                ],
                'edit' => [
                    'route' => 'ergonode_workflow_transition_change',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'from' => '{from}',
                        'to' => '{to}',
                    ],
                    'privilege' => 'ERGONODE_ROLE_WORKFLOW_PUT_TRANSITION',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_workflow_transition_delete',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'from' => '{from}',
                        'to' => '{to}',
                    ],
                    'privilege' => 'ERGONODE_ROLE_WORKFLOW_DELETE_TRANSITION',
                    'method' => Request::METHOD_DELETE,
                ],
            ]));

        return $grid;
    }

    private function getStatuses(Language $language): array
    {
        $result = [];
        foreach ($this->statusQuery->getAllStatuses($language) as $code => $status) {
            $result[] = new StatusOption($code, $status['code'], new Color($status['color']), $status['name']);
        }

        return $result;
    }

    private function getRoles(): array
    {
        $result = [];
        foreach ($this->roleQuery->getDictionary() as $key => $value) {
            $result[] = new LabelFilterOption($key, $value);
        }

        return $result;
    }
}
