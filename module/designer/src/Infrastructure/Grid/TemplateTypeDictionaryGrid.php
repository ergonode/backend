<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class TemplateTypeDictionaryGrid extends AbstractGrid
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
        $this->addColumn('type', new TextColumn('type', $this->trans('Type'), new TextFilter()));
        $this->addColumn('variant', new TextColumn('variant', $this->trans('Variant'), new TextFilter()));
        $this->addColumn('label', new TextColumn('label', $this->trans('Label'), new TextFilter()));
        $this->addColumn('min_width', new IntegerColumn('min_width', $this->trans('Minimal width'), new TextFilter()));
        $this->addColumn('min_height', new IntegerColumn('min_height', $this->trans('Minimal height'), new TextFilter()));
        $this->addColumn('max_width', new IntegerColumn('max_width', $this->trans('Maximal width'), new TextFilter()));
        $this->addColumn('max_height', new IntegerColumn('max_height', $this->trans('Maximal height'), new TextFilter()));
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
