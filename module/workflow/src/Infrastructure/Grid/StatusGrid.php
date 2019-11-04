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
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class StatusGrid extends AbstractGrid
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
        foreach ($statuses as $id => $status) {
            $codes[$id] = $status['name'];
        }

        $id = new TextColumn('id', $this->trans('Id'), new TextFilter($filters->get('id')));
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $column = new TextColumn('code', $this->trans('Code'), new TextFilter($filters->get('code')));
        $this->addColumn('code', $column);

        $code = new LabelColumn('status', $this->trans('Status'), $statuses, new SelectFilter($codes, $filters->get('status')));
        $this->addColumn('status', $code);

        $column = new TextColumn('name', $this->trans('Name'), new TextFilter($filters->get('name')));
        $this->addColumn('name', $column);

        $column = new TextColumn('description', $this->trans('Description'), new TextFilter($filters->get('description')));
        $this->addColumn('description', $column);

        $column = new BoolColumn('is_default', $this->trans('Default'));
        $this->addColumn('is_default', $column);

        $this->orderBy('code', 'DESC');

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
