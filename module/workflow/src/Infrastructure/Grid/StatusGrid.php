<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ActionColumn;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
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
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {

        $filters = $configuration->getFilters();

        $id = new TextColumn('id', $this->trans('Id'), new TextFilter($filters->getString('code')));
        $id->setVisible(false);
        $id->setWidth(140);
        $this->addColumn('id', $id);
        $this->addColumn('code', new LabelColumn('code', 'color', $this->trans('Code'), new TextFilter($filters->getString('code'))));
        $column = new TextColumn('name', $this->trans('Name'), new TextFilter($filters->getString('name')));
        $column->setWidth(200);
        $this->addColumn('name', $column);
        $column = new TextColumn('description', $this->trans('Description'), new TextFilter($filters->getString('description')));
        $column->setWidth(300);
        $this->addColumn('description', $column);
        $this->addColumn('edit', new ActionColumn('edit'));
        $this->orderBy('code', 'DESC');
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
