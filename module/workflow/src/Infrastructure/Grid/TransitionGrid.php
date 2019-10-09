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
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class TransitionGrid extends AbstractGrid
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var StatusQueryInterface
     */
    private $statusQuery;

    /**
     * @param TranslatorInterface  $translator
     * @param StatusQueryInterface $statusQuery
     */
    public function __construct(TranslatorInterface $translator, StatusQueryInterface $statusQuery)
    {
        $this->translator = $translator;
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
        $filters = $configuration->getFilters();
        $codes = [];
        foreach ($statuses as $code => $status) {
            $codes[$code] = $status['name'];
        }

        $code = new LabelColumn('source', $this->trans('Source'), $statuses, new SelectFilter($codes, $filters->getString('source')));
        $this->addColumn('source', $code);

        $code = new LabelColumn('destination', $this->trans('Destination'), $statuses, new SelectFilter($codes, $filters->getString('destination')));
        $this->addColumn('destination', $code);

        $column = new TextColumn('name', $this->trans('Name'), new TextFilter($filters->getString('name')));
        $this->addColumn('name', $column);

        $column = new TextColumn('description', $this->trans('Description'), new TextFilter($filters->getString('description')));
        $this->addColumn('description', $column);

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_workflow_transition_read',
                'parameters' => ['language' => $language->getCode(), 'source' => '{source}', 'destination' => '{destination}'],
            ],
            'edit' => [
                'route' => 'ergonode_workflow_transition_change',
                'parameters' => ['language' => $language->getCode(), 'source' => '{source}', 'destination' => '{destination}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_workflow_transition_delete',
                'parameters' => ['language' => $language->getCode(), 'source' => '{source}', 'destination' => '{destination}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('code', 'DESC');

        $this->setConfiguration(AbstractGrid::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }

    /**
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, 'grid');
    }
}
