<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ActionColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use Symfony\Component\Translation\TranslatorInterface;

/**
 */
class SegmentGrid extends AbstractGrid
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

        $statuses = array_combine(SegmentStatus::AVAILABLE, SegmentStatus::AVAILABLE);

        $id = new TextColumn('id', $this->trans('Id'), new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('name', $this->trans('Code'), new TextFilter($filters->getString('code'))));
        $this->addColumn('status', new TextColumn('status', $this->trans('Status'), new SelectFilter($statuses, $filters->getString('status'))));
        $this->addColumn('name', new TextColumn('name', $this->trans('Name'), new TextFilter($filters->getString('name'))));
        $this->addColumn('description', new TextColumn('description', $this->trans('Description'), new TextFilter($filters->getString('description'))));
        $this->addColumn('edit', new ActionColumn('edit'));
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
        $this->orderBy('id', 'DESC');
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
