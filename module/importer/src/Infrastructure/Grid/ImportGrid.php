<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ActionColumn;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 */
class ImportGrid extends AbstractGrid
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

        $id = new TextColumn('id', $this->trans('Id'), new TextFilter());
        $this->addColumn('id', $id);
        $id->setWidth(240);
        $name = new TextColumn('name', $this->trans('Name'), new TextFilter());
        $name->setWidth(240);
        $this->addColumn('name', $name);
        $status = new TextColumn('status', $this->trans('Status'), new TextFilter());
        $status->setWidth(140);
        $this->addColumn('status', $status);
        $index = new IntegerColumn('lines', $this->trans('Lines'), new TextFilter());
        $index->setWidth(140);
        $this->addColumn('lines', $index);
        $createdAt = new DateColumn('created_at', $this->trans('Created at'), new TextFilter());
        $createdAt->setWidth(140);
        $this->addColumn('created_at', $createdAt);
        $this->addColumn('info', new ActionColumn('info'));
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
