<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 */
class TemplateGrid extends AbstractGrid
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var TemplateGroupQueryInterface
     */
    private $query;

    /**
     * DesignerTemplateGrid constructor.
     *
     * @param TranslatorInterface         $translator
     * @param TemplateGroupQueryInterface $query
     */
    public function __construct(TranslatorInterface $translator, TemplateGroupQueryInterface $query)
    {
        $this->translator = $translator;
        $this->query = $query;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $collection = $this->query->getDictionary();

        $this->addColumn('id', new TextColumn('id', $this->trans('Id')));
        $this->addColumn('name', new TextColumn('name', $this->trans('Name'), new TextFilter()));
        $this->addColumn('image_id', new TextColumn('image_id', $this->trans('Icon'), new TextFilter()));
        $this->addColumn('group_id', new TextColumn('group_id', $this->trans('Group'), new SelectFilter($collection)));
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
