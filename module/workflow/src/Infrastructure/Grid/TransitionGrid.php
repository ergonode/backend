<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class TransitionGrid extends AbstractGrid
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
        foreach ($statuses as $code => $status) {
            $codes[] = new FilterOption($code, $code, $status['name']);
        }

        $code = new LabelColumn('source', 'From', new MultiSelectFilter($codes));
        $this->addColumn('source', $code);

        $code = new LabelColumn('destination', 'To', new MultiSelectFilter($codes));
        $this->addColumn('destination', $code);

        $this->addColumn('_links', new LinkColumn('hal', [
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
    }
}
