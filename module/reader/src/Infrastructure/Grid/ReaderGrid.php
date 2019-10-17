<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ReaderGrid extends AbstractGrid
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

        $id = new TextColumn('id', $this->trans('Id'));
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $status = new TextColumn('name', $this->trans('Name'), new TextFilter($filters->get('name')));
        $this->addColumn('name', $status);
        $type = new IntegerColumn('type', $this->trans('Type'), new TextFilter($filters->get('type')));
        $this->addColumn('type', $type);
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_reader_read',
                'parameters' => ['language' => $language->getCode(), 'reader' => '{id}'],
            ],
            'delete' => [
                'route' => 'ergonode_reader_delete',
                'parameters' => ['language' => $language->getCode(), 'reader' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
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
