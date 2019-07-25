<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ActionColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class CategoryGrid extends AbstractGrid
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

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $index = new IntegerColumn('sequence', $this->trans('Index'), new TextFilter($filters->getString('sequence')));
        $index->setWidth(40);
        $this->addColumn('sequence', $index);
        $name = new TextColumn('name', 'Name', new TextFilter($filters->getString('name')));
        $name->setWidth(280);
        $this->addColumn('name', $name);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter($filters->getString('code'))));
        $this->addColumn('elements_count', new IntegerColumn('elements_count', $this->trans('Number of products'), new TextFilter($filters->getString('elements_count'))));
        $this->addColumn('edit', new ActionColumn('edit'));
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, false);
        $this->orderBy('sequence', 'DESC');
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
