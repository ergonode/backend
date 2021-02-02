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

class TransitionGridBuilder implements GridBuilderInterface
{
    private StatusQueryInterface $statusQuery;

    public function __construct(StatusQueryInterface $statusQuery)
    {
        $this->statusQuery = $statusQuery;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $codes = $this->getStatuses($language);

        $grid = new Grid();

        $grid
            ->addColumn('source', new LabelColumn('source', 'From', new MultiSelectFilter($codes)))
            ->addColumn('destination', new LabelColumn('destination', 'To', new MultiSelectFilter($codes)))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_workflow_transition_read',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'source' => '{source}',
                        'destination' => '{destination}',
                    ],
                    'privilege' => 'WORKFLOW_READ',
                ],
                'edit' => [
                    'route' => 'ergonode_workflow_transition_change',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'source' => '{source}',
                        'destination' => '{destination}',
                    ],
                    'privilege' => 'WORKFLOW_UPDATE',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_workflow_transition_delete',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'source' => '{source}',
                        'destination' => '{destination}',
                    ],
                    'privilege' => 'WORKFLOW_DELETE',
                    'method' => Request::METHOD_DELETE,
                ],
            ]));

        return $grid;
    }

    private function getStatuses(Language $language): array
    {
        $result = [];
        foreach ($this->statusQuery->getAllStatuses($language) as $code => $status) {
            $result[] = new StatusOption($code, $code, new Color($status['color']), $status['name']);
        }

        return $result;
    }
}
